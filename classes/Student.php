<?php
require_once('init.php');

class Student extends DatabaseObject {
	
	protected static $table_name="students";
	public $firstName;
	public $lastName;
	public $id;
	public $address;
	public $phoneNumber;


	protected static $db_fields = array('username', 'password');

	public static function authenticate($username="", $password=""){
		global $DatabaseObject;
		global $connection;
		$sql = "SELECT * FROM $table_name
				WHERE username = ?
				AND password = ?
				LIMIT 1";

		$found_user = $connection->prepare($sql);
		$found_user->bindParam(1, $username);
		$found_user->bindParam(2, $password);
		$found_user->execute();

		$found = $found_user->fetch(PDO::FETCH_OBJ);

		return $found;
	}

	public function create_user(){
		global $connection;
		$sql = "INSERT INTO $table_name
				(`username`, `password`)
				VALUES('{$this->username}', '{$this->password}')";
		if($connection->query($sql)){
			echo "created";
		} else {
			echo "Error";
		}
	}

	public function full_name() {
		//$student = Self::find_by_id($id);
		return $this->firstName . " " . $this->lastName;
	}

	public function full_name_by_id($id) {
		$student = Self::find_by_id($id);
		return $student->firstName . " " . $student->lastName;
	}

}

