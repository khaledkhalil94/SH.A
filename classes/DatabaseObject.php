<?php 
require_once('init.php');
class DatabaseObject {

	// static $magic_quotes_active;
	 public $real_escape_string_exists;

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
		$sql = "UPDATE ".static::$table_name;
		$sql .= " SET ";
		$array = $this->attributes();
		$copy = $array;
		foreach ($array as $key => $value) {
			$comma = ", ";
			if(!next($copy)) $comma = "";
				$sql .= "`{$key}`" . " = " . "'{$value}'" . $comma;
		}
		$sql .= " ";
		$sql .= " WHERE id = {$this->id}";

		if(!$connection->query($sql)){
			echo "<br>";
			$error = ($connection->errorInfo());
			echo $error[2];
		}
	}

	public function create(){
		global $connection;
		$sql = "INSERT INTO ".static::$table_name;
		$sql .=	" (`";
		$sql .=	implode("`, `", array_keys($this->attributes()));
		$sql .= "`) VALUES ('";
		$sql .= implode("', '", array_values($this->attributes()));
		$sql .=  "')";

		if($connection->query($sql)){
			echo "created";
			 header('Location:index.php');
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
				$attributes[$field] = $this->escape_value($this->$field);
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

	public function escape_value($value) {
		global $connection;
		if( $this->real_escape_string_exists ) { // PHP v4.3.0 or higher
			// undo any magic quote effects so mysql_real_escape_string can do the work
			if( $this->magic_quotes_active ) { $value = stripslashes( $value ); }
			$value = $connection->quote($value);
		}
		return $value;
	}

}

$DatabaseObject = new DatabaseObject();

?>