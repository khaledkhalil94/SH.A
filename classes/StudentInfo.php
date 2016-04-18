<?php
require_once('init.php');

class StudentInfo extends DatabaseObject {
	
	protected static $table_name="login_info";
	public $id;
	public $username;
	public $password;
	public $email;


	protected static $db_fields = array('id', 'username', 'password', 'email');

	// public function update(){
	// 	global $connection;
	// 	$sql = "UPDATE ".self::$table_name;
	// 	$sql .= " SET username = '{$this->username}'";
	// 	$sql .= " WHERE id = {$this->id}";

	// 	if(!$connection->query($sql)){
	// 		echo "<br>";
	// 		$error = ($connection->errorInfo());
	// 		echo $error[2];
	// 	}
	// }

}

