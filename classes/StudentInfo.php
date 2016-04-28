<?php
require_once('init.php');

class StudentInfo extends User {
	
	protected static $table_name="login_info";
	public $id;
	public $username;
	public $password;
	public $email;
	public $type;


	protected static $db_fields = array('id', 'username', 'password', 'email', 'type');

	public static function create_student(){
		global $connection;
		global $session;

		$user = self::create_user();

		$sql = "INSERT INTO students (`id`) VALUES ('{$user->id}')" ;
		$stmt = $connection->prepare($sql);

		if($stmt->execute()){
			$session->login($user);
		 	$session->message("Thanks for signing up, please update your information");
			 	header("Location:".BASE_URL."students/".$user->id."/");

		} else {
			echo "err<br>";
			$error = $stmt->errorInfo();
			echo $error[2];
		}

	}



}

