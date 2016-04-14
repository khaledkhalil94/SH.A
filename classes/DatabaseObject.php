<?php 
require_once('init.php');
class DatabaseObject {

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

	// first, create an empty assoc array $attributes
	// iterate through every db_field using a foreach loop
	// in each step, assign the key to the array to the value fields
	// for example, $attributes[username] = $this-username = ~the inserted value.
	public function attributes(){
		$attributes = array();
		foreach (DB::$db_fields as $field) {
			if(property_exists($this, $field)){
				$attributes[$field] = $this->$field;
			}
		}
		return $attributes;
	}

	public function create(){
		global $connection;
		$sql = "INSERT INTO login_info
				(`id`, `username`, `password`)
				VALUES( null, '{$this->username}', '{$this->password}')";
		if($connection->query($sql)){
			echo "created";
		} else {
			echo "Error";
		}
	}

}

$DatabaseObject = new DatabaseObject();

?>