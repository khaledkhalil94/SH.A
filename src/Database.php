 <?php 
require_once( $_SERVER["DOCUMENT_ROOT"] .'/sha/src/init.php');

/**
 * Database class, opens the connection to the database 
 * Handles main database queries
 * Escaped and fully reusable database queries
 */

class Database {

	/**
	 * @var object database pdo connection
	 *
	 */
	private $connection;

	/**
	 * @var int number of rows affected
	 *
	 */
	public $rowCount;

	/**
	 * @var boolean $error error status
	 */
	public $error=false;

	/**
	 * @var array of errors
	 */
	public $errors = [];

	/**
	 * @var int id of last executed query
	 */
	public $lastId;


	function __construct() {
		global $connection;
		$dsn = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST;
		try {
			$connection = new PDO($dsn,DB_USER,'');

			$this->connection = $connection;
			//echo "Connected to database.<br>";
		} catch (PDOException $e) {
			die($e->getMessage());
		}
	}

	/**
	* Inserts a row into a table after escaping it's values
	*
	* @param string => table name
	* @param object => the data to be inserted
	*
	* @return boolean true | string
	*
	*/
	public function insert_data($table, $data){

		$fields = [];
		$values = [];
		$params = [];
		foreach ($data as $k => $v) {
			$fields[] = '`'.$k.'`';
			$values[] = ':'.$k;
			$params[":".$k] = $v;
		}

		$sql = "INSERT INTO `{$table}` (" . implode(", ", $fields) . ") VALUES (" . implode(", ", $values) . ")";

		$stmt = $this->connection->prepare($sql);
		if(!$stmt->execute($params)){
			$error = $stmt->errorInfo();
			$this->error = true;
			$this->errors[] = $error;
			return false;
		}

		$this->lastId = $this->connection->lastInsertId();
		return true;
	}


	/**
	* Updates a row in a table after escaping it's values
	*
	* @param $table string => table name
	* @param $fields mixed
	* @param $vlaues mixed
	* @param $where string
	* @param $rule string (default user_id)
	*
	* fields and values can either be a string of element or array of elements
	*
	* @return boolean
	*
	*/
	public function update_data($table, $fields, $values, $where, $rule=USER_ID){

		if(count($fields) !== count($values)){
			$this->error = true;
			$this->errors[] = "Fields count doesn't match values count.";
			return false;
		}

		$set = '';
		$r_values = []; // values to be escaped when executing


		$source = array_combine($fields, $values);
		foreach ($fields as $field) {

			if (isset($source[$field])) {
				$set.="`$field`=:$field, ";

				$r_values[$field] = $source[$field];

			}
		}

		// remove the last coma from the set
		$set = substr($set, 0, -2); 

		$sql = "UPDATE `{$table}` SET $set WHERE {$where} = {$rule}";

		$stmt = $this->connection->prepare($sql);
		$res = $stmt->execute($r_values);

		if(!$res) {
			$error = $stmt->errorInfo();

			$this->error = true;
			$this->errors[] = $error[2];

			return false;

		 } else {
			return true;
		 }
	}


	/**
	* checks a table and return whether a row exists or not
	*
	* @param $table string
	* @param $where string
	* @param $rule string
	*
	* @return boolean
	*
	*/
	public function row_exists($table, $where, $rule){

		$sql = "SELECT 1 FROM `{$table}` WHERE $where = ?";

		$stmt = $this->connection->prepare($sql);
		
		$stmt->bindParam(1, $rule);

		if(!$stmt->execute()){
			$error = $stmt->errorInfo();
			return $error;
		}

		$exists = (bool)$stmt->fetch();

		return $exists;

	}


	/**
	* execute a query string
	*
	* @param $sql string
	*
	* @return boolean|string
	*
	*/
	public function xcute($sql){

		$stmt = $this->connection->prepare($sql);

		if(!$stmt->execute()){
			$error = $stmt->errorInfo();
			return $error;
		}

		return true;
	}
}
	

$database = new Database();

?>