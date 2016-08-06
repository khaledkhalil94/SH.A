<?php 
require_once( $_SERVER["DOCUMENT_ROOT"] .'/sha/classes/init.php');
class Database {

	protected static $db_fields = array('firstName', 'lastName', 'id');

	function __construct() {
		global $connection;
		$dsn = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST;
		try {
			$connection = new PDO($dsn,DB_USER,'');
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
	public function insert_data($db, $data){
		global $connection;

		$fields = [];
		$values = [];
		$params = [];
		foreach ($data as $k => $v) {
			$fields[] = '`'.$k.'`';
			$values[] = ':'.$k;
			$params[":".$k] = $v;
		}

		$sql = "INSERT INTO `{$db}` (" . implode(", ", $fields) . ") VALUES (" . implode(", ", $values) . ")";

		$stmt = $connection->prepare($sql);
		if(!$stmt->execute($params)){
			$error = $stmt->errorInfo();
			return $error[2];
		}

		return true;
	}

	
}

$database = new Database();

?>