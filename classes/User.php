<?php 
require_once('init.php');
class User {



// To be removed
	public static function find_by_id($id, $msql=""){
		$sql = "SELECT * FROM " .static::$table_name." WHERE id={$id}";
		if(!empty($msql)) $sql .= $msql;
		$found = static::find_by_sql($sql);
		return !empty($found) ? array_shift($found) : false;
	}

// To be removed
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
		//exit(print_r($_POST));
		$object = $this->instantiate($_POST);

		$fields = array_keys((array)$object);

		$sql = "UPDATE ".static::$table_name . " SET ".$this->pdoSet($object,$fields,$values)." WHERE id = {$object->id}";

		$stmt = $connection->prepare($sql);

		$res = $stmt->execute($values);

		if(!$res) {
			//exit($sql);
			$error = $stmt->errorInfo();
			return array('status' => false, 'errMsg' => $error[2]);
			$_SESSION['err'] = $error[2];

		 } else {
			return true;
		 }
	}

	public function pdoSet($object,$fields, &$values, $source = array()) {
	  $set = '';
	  $values = array();
	  $array = (array)$object;
	  if (!$source) $source = &$array;
	  foreach ($fields as $field) {
	  	if($field == 'id') continue;
	    if (isset($source[$field])) {
	      $set.="`$field`=:$field, ";
	      $values[$field] = $source[$field];
	    }
	  }

	  return substr($set, 0, -2); 
	}

	protected static function instantiate($data){
		$object = new static;

		foreach ($data as $attribute => $value) {
			if ($object->has_attribute($attribute)) {
				$object->$attribute = trim($value);
			}
		}

		return $object;
	}

	private function has_attribute($attribute){
		$object_vars = get_object_vars($this);

		return array_key_exists($attribute, $object_vars);
	}

	public static function create_user(){
		$user = self::instantiate($_POST);
		//$user->type = $_POST['type'];
		$user->create();
		return $user;
		// } else {
		// 	return $user;
		// 	return false;
		// }
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
			return $error[2];
				// $session->message("Username is already taken, please choose another one.", "", "danger");
		}
	}
	
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

	public static function get_count($msql=""){
		global $connection;
		$sql = "SELECT count(*) FROM ".static::$table_name;
		if(!empty($msql)) $sql .= $msql;
		$res = $connection->query($sql);
		return $res->fetch()[0];
	}
	
	public static function query($sql){
		global $connection;
		$stmt = $connection->prepare($sql);

		if(!$stmt->execute()){
			$error = ($stmt->errorInfo());
			echo $error[2];
			return false;
		}
		return true;
	}
}

?>