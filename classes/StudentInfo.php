<?php
require_once('init.php');

class StudentInfo extends DatabaseObject {
	
	protected static $table_name="login_info";
	public $id;
	public $username;
	public $password;
	public $email;


	protected static $db_fields = array('id', 'username', 'password', 'email');

	public static function authenticate($username="", $password=""){
		//global $DatabaseObject;
		global $connection;
		$sql = "SELECT * FROM login_info
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

}

