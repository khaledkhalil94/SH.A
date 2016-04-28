<?php
require_once('init.php');

class StaffInfo extends User {
	
	protected static $table_name="staff";
	public $id;
	public $username;
	public $password;
	public $email;




	protected static $db_fields = array('id', 'username', 'password', 'email');

	public static function create_staff(){
		global $connection;
		global $session;

		$user = self::create_user();

		$sql = "INSERT INTO professor (`id`) VALUES ('{$user->id}')" ;
		$stmt = $connection->prepare($sql);

		if($stmt->execute()){
			$session->login($user);
		 	$session->message("Thanks for signing up, please update your information");
			 	//header("Location:".BASE_URL."professors/".$user->id."/");

		} else {
			echo "err<br>";
			$error = $stmt->errorInfo();
			echo $error[2];
		}

	}

}
