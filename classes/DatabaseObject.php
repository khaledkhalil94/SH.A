<?php 
require_once('init.php');
class DatabaseObject {

	// static $magic_quotes_active;
	 //public $real_escape_string_exists;



	public static function find_all_students(){
		$sql = "SELECT * FROM " .static::$table_name;
		$all = static::find_by_sql($sql);
		return $all;
	}

	public static function find_by_id($id){
		$sql = "SELECT * FROM " .static::$table_name." WHERE id={$id}";
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

	private static function instantiate($student){
		$object = new static;

		foreach ($student as $attribute => $value) {
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

	public function update(){
		global $connection;

		$class = get_called_class();
		$fields = array_keys((array)$this);

		$sql = "UPDATE ".static::$table_name . " SET ".$this->pdoSet($fields,$values)." WHERE id = {$this->id}";
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

	
	private function pdoSet($fields, &$values, $source = array()) {
	  $set = '';
	  $values = array();
	  $array = (array)$this;
	  if (!$source) $source = &$array;
	  foreach ($fields as $field) {
	    if (isset($source[$field])) {
	      $set.="`$field`=:$field, ";
	      $values[$field] = $source[$field];
	    }
	  }

	  return substr($set, 0, -2); 
	}


	public function create_user(){
		global $connection;
		global $session;
		// Create new user in the login_info table
		$sql = "INSERT INTO ".static::$table_name;
		$sql .=	" (`";
		$sql .=	implode("`, `", array_keys($this->attributes()));
		$sql .= "`) VALUES ('";
		$sql .= implode("', '", array_values($this->attributes()));
		$sql .=  "')";
		$createl = $connection->prepare($sql);

		//register the user in the students table
		$sql = "INSERT INTO students (`id`) VALUES ('{$this->id}')";
		$creates = $connection->prepare($sql);

		if($createl->execute() && $creates->execute()){
			$session->login_by_id($this->id);
		 	$session->message("Thanks for signing up, please update your information");
			 	header("Location:".BASE_URL."students/".$this->id."/");

		} else {
			echo "<br>";
			$error = ($connection->errorInfo());
			echo $error[2];
		}
	}


	// first, create an empty assoc array $attributes
	// iterate through every db_field using a foreach loop
	// in each step, assign the key to the array to the value fields
	// for example, $attributes[username] = $this-username = ~the inserted value.
	
	public function attributes(){
		$attributes = array();
		foreach (static::$db_fields as $field) {
			if(property_exists($this, $field)){
				$attributes[$field] = $this->$field;
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

}

$DatabaseObject = new DatabaseObject();

	// public function update(){
	// 	global $connection;
	// 	$sql = "UPDATE ".static::$table_name;
	// 	$sql .= " SET ";
	// 	$array = array();
	// 	foreach ($this->attributes() as $key => $value) {
	// 		$array[] = "{$key} = '{$value}'"; 
	// 	}
	// 	$sql .= implode(", ", $array);
	// 	$sql .= " WHERE id = {$this->id}";
	// 	$sql = "UPDATE ".static::$table_name . " SET username = :username WHERE id = {$this->id}";


	// 	$result = $connection->prepare($sql);
	// 	$result->bindParam(':username', $this->username);

	// 	$res = $result->execute();

	// 	if(!$res){
	// 		$error = ($connection->errorInfo());
	// 		echo $error[2];
	// 	}
	// }

	// public function updatee(){
	// 	global $connection;

	// 	$class = get_called_class();

	// 	$fields = array_keys((array)$this);

	// 	//$sql = "UPDATE ".static::$table_name . " SET ".$this->pdoSet($fields,$values)." WHERE id = {$this->id}";
	// 	$sql = "UPDATE students SET firstName = 'tzestaaaaaaaaaaaaaaa22' WHERE id = '5501'";
	// 	$stmt = $connection->prepare($sql);
	// 	// $stmt->bindValue(1, "testaaaaaaaaaaaaaaaa22");
	// 	$res = $stmt->execute();
	// 	var_dump($res);

		// if (!$res) {
		// 	//return false;
		// } 
		//return true;
		
		// if($res) {
		// 	$_SESSION['success']['class'] = "";
		// 	$_SESSION['success']['class'] .= $class . " - ";
		// 	//var_dump($res);
		// 	//return true;

		// } else {
		// 	$error = ($stmt->errorInfo());
		// 	echo $sql;
		// 	$_SESSION['fail']['sqlerr'] = $error[2];
		// 	$_SESSION['fail']['class'] = $class;
		// 	return false;
		// }
		//return true;
	//}
?>