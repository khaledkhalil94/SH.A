<?php
require_once('init.php');

class StudentInfo extends User {
	
	protected static $table_name="login_info";
	public $id,	$username, $password, $email, $type="student", $activity, $register_date;
	protected static $db_fields = array();

	public function __construct(){
		global $db_fields;
		self::$db_fields = array_keys((array)$this);
	}

	public static function create_student(){
		global $connection;
		global $session;
		global $StudentInfo;
		$user = self::create_user();
		//if(!$user) return false;
		$sql = "INSERT INTO students (`id`) VALUES ('{$user->id}')";
		$stmt = $connection->prepare($sql);

		if($user && $stmt->execute()){
			return $user;
			// $session->login($user);
		 	// $session->message("Thanks for signing up, please update your information");
   			// self::log("signup", $user);
			// header("Location:".BASE_URL."students/".$user->id."/");

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