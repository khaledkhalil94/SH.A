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
		global $StudentInfo;
		$user = self::create_user();

		$sql = "INSERT INTO students (`id`) VALUES ('{$user->id}')";
		$stmt = $connection->prepare($sql);

		if($stmt->execute()){
			$session->login($user);
		 	$session->message("Thanks for signing up, please update your information");
     		self::log("signup", $user);
			 	header("Location:".BASE_URL."students/".$user->id."/");

		} else {
			echo "err<br>";
			$error = $stmt->errorInfo();
			echo $error[2];
		}

	}

	public static function log($case, $user){
		global $connection;
		switch ($case) {
			case 'login':
				$log = "{$user->username} has logged in";
				break;

			case 'signup':
				$log = "{$user->username} has signed up";
				break;

			case 'logout':
				$log = "{$user->username} has logged out";
				break;
			
			default:
				# code...
				break;
		}
		$sql = "INSERT INTO `logs` (`log`, `user`, `user_id`) VALUES ('{$log}', '{$user->username}', '{$user->id}')";
		$stmt = $connection->prepare($sql);
		$res = $stmt->execute();
		if(!$res) {
			$error = ($stmt->errorInfo());
			echo $error[2];
		}
	}
}
$StudentInfo = new StudentInfo();