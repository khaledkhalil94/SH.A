<?php
require_once('init.php');

class StudentInfo extends User {
	
	protected static $table_name="staff";
	public $id;
	public $username;
	public $password;
	public $email;


	protected static $db_fields = array('id', 'username', 'password', 'email', 'type');

	public static function create_user(){
		global $connection;
		global $session;
		$user = parent::instantiate($_POST);
		$user->type = $_POST['type']; 
		if($user->create()){
			echo "here";
			$sql = "INSERT INTO students (`id`) VALUES ('{$user->id}')";
			$creates = $connection->prepare($sql);

			$sql = "INSERT INTO profile_pic (`user_id`) VALUES ('{$user->id}')";
			$createp = $connection->prepare($sql);


			if($creates->execute() && $createp->execute()){
				$session->login_by_id($user->id);
			 	$session->message("Thanks for signing up, please update your information");
				 	header("Location:".BASE_URL."students/".$user->id."/");

			} else {
				echo "err<br>";
				$error = $creates->errorInfo();
				echo $error[2];
			}
		}
	}

	public static function authenticate($username="", $password=""){
		global $connection;
		$sql = "SELECT * FROM staff
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

