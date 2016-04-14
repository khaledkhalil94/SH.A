<?php
require_once('Database.php');

class Student extends DatabaseObject {
	
	protected static $table_name="students";
	public $firstName;
	public $lastName;
	public $id;
	public $address;

	protected static $db_fields = array('firstName', 'lastName', 'id');

	public static function authenticate($username="", $password=""){
		global $connection;

		$sql = "SELECT * FROM login_info
				WHERE username = '{$username}'
				AND password = '{$password}'
				LIMIT 1";

		$found_user = $connection->prepare($sql);
		$found_user->execute();

		$found = $found_user->fetch(PDO::FETCH_OBJ);

		return $found;
	}

	// public static function find_all_students(){
	// 	$sql = "SELECT * FROM students";
	// 	$all = self::find_by_sql($sql);
	// 	return $all;
	// }

	// public static function find_by_id($id){
	// 	$sql = "SELECT * FROM students WHERE id={$id}";
	// 	$found = self::find_by_sql($sql);
	// 	return !empty($found) ? array_shift($found) : false;
	// }

	// public static function find_by_sql($sql=""){
	// 	global $connection;

	// 	$result = $connection->prepare($sql);
	// 	$result->execute();
	// 	$object_array = array();
	// 	while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
	// 		$object_array[] = self::instantiate($row);
	// 	}
	// 	return $object_array;
	// }

	// private static function instantiate($student){
	// 	$object = new self;

	// 	foreach ($student as $attribute => $value) {
	// 		if ($object->has_attribute($attribute)) {
	// 			$object->$attribute = $value;
	// 		}
	// 	}

	// 	return $object;
	// }

	// private function has_attribute($attribute){
	// 	$object_vars = get_object_vars($this);

	// 	return array_key_exists($attribute, $object_vars);
	// }

	// // first, create an empty assoc array $attributes
	// // iterate through every db_field using a foreach loop
	// // in each step, assign the key to the array to the value fields
	// // for example, $attributes[username] = $this-username = ~the inserted value.
	// public function attributes(){
	// 	$attributes = array();
	// 	foreach (DB::$db_fields as $field) {
	// 		if(property_exists($this, $field)){
	// 			$attributes[$field] = $this->$field;
	// 		}
	// 	}
	// 	return $attributes;
	// }

	public static function full_name($id) {
		$student = Self::find_by_id($id);
		return $student->firstName . " " . $student->lastName;
	}

}

