<?php 
require_once('init.php');
class User {

	public static function authenticate($username="", $password=""){
		global $connection;
		$sql = "SELECT * FROM ". static::$table_name."
				WHERE username = ?
				AND password = ?
				LIMIT 1";

		$found_user = $connection->prepare($sql);
		$found_user->bindParam(1, $username);
		$found_user->bindParam(2, $password);
		$found_user->execute();
		return $found_user->fetch(PDO::FETCH_OBJ);
	}

	public static function get_all_users(){
		$sql = "SELECT * FROM " .static::$table_name;
		$all = static::find_by_sql($sql);
		return $all;
	}

	public static function find_by_id($id, $msql=""){
		$sql = "SELECT * FROM " .static::$table_name." WHERE id={$id}";
		if(!empty($msql)) $sql .= $msql;
		$found = static::find_by_sql($sql);
		return !empty($found) ? array_shift($found) : false;
	}

	public static function find_by_sql($sql=""){
		global $connection;

		$result = $connection->prepare($sql);
		$result->execute();
		$object_array = array();
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$object_array[] = static::instantiate($row);
		}
		return $object_array;
	}

	public function update(){
		global $connection;
		$object = $this->instantiate($_POST);
		//exit(print_r($this));
		$class = get_called_class();
		$fields = array_keys((array)$object);

		$sql = "UPDATE ".static::$table_name . " SET ".$this->pdoSet($object,$fields,$values)." WHERE id = {$object->id}";
		$stmt = $connection->prepare($sql);
		$res = $stmt->execute($values);

		if(!$res) {
			$error = ($connection->errorInfo());
			$_SESSION['fail']['sqlerr'] = $error[2];
			$_SESSION['fail']['class'] .= $class . " ";
			//var_dump($res);
			return false;
		 } 
		return true;
	}

	private function pdoSet($object,$fields, &$values, $source = array()) {
	  $set = '';
	  $values = array();
	  $array = (array)$object;
	  if (!$source) $source = &$array;
	  foreach ($fields as $field) {
	    if (isset($source[$field])) {
	      $set.="`$field`=:$field, ";
	      $values[$field] = $source[$field];
	    }
	  }

	  return substr($set, 0, -2); 
	}

	protected static function instantiate($user){
		$object = new static;

		foreach ($user as $attribute => $value) {
			if ($object->has_attribute($attribute)) {
				$object->$attribute = $value;
			}
		}

		return $object;
	}

	private function has_attribute($attribute){
		$object_vars = get_object_vars($this);

		return array_key_exists($attribute, $object_vars);
	}

	public static function create_user(){
		global $connection;
		$user = self::instantiate($_POST);
		$user->type = $_POST['type']; 
		if($user->create()){
			return $user;
		} else {
			return false;
		}
	}

	protected function create(){
		global $connection;

		$sql = "INSERT INTO ".static::$table_name;
		$sql .=	" (`";
		$sql .=	implode("`, `", array_keys($this->attributes($values)));
		$sql .= "`) VALUES (:";
		$sql .= implode(", :", array_keys($this->attributes($values)));
		$sql .=  ")";

		$stmt = $connection->prepare($sql);
		//exit($sql);
		if($stmt->execute($values)){
			return true;
		}else {
			$error = ($stmt->errorInfo());
			echo $error[2];
		}
	}

	// first, create an empty assoc array $attributes
	// iterate through every db_field using a foreach loop
	// in each step, assign the key to the array to the value fields
	// for example, $attributes[username] = $this->username = ~the inserted value.
	
	public function attributes(&$values){
		$attributes = array();
		$values = array();
		foreach (static::$db_fields as $field) {
			if(property_exists($this, $field)){
				$attributes[$field] = $this->$field;
				$values[":".$field] = $this->$field;
			}
		}
		return $attributes;
	}

	public function validate_username($value){
		$value = trim($value);
		if (isset($value) && $value !== ""){
			return $value;
		} else {
			exit("Username can't be empty");
		}
	}

	public function validate_password($value){
		if (empty($value)){
			exit("Password can't be empty");
		} elseif(strlen($value) < 2) {
			exit("Password must be at least 2 characters long");
		}
		return $value;
	}

	public static function get_count(){
		global $connection;
		$res = $connection->query("SELECT count(*) FROM ".static::$table_name);
		return $res->fetch()[0];

	}

	public static function get_faculty($id){
		global $connection;

		$sql = "SELECT name FROM faculties ";
		$sql .= "WHERE id = {$id} ";
		$sql .= "LIMIT 1";

		$stmt = $connection->query($sql)->fetch(PDO::FETCH_ASSOC);
		if($stmt){
			return ucwords(str_replace("_", " ", $stmt['name']));
		}
		if(!$stmt) {
			$error = ($connection->errorInfo());
			echo $error[2];
		}
	}
	
	public function full_name() {
		return $this->firstName . " " . $this->lastName;
	}

	public static function get_users($rpp,$offset){
		$sql = "SELECT * FROM ".static::$table_name." LIMIT {$rpp} OFFSET {$offset}";
		return self::find_by_sql($sql);
	}

	public static function displayPag(){
		Pagination::display(static::get_count());
	}
}

$User = new User();

?>