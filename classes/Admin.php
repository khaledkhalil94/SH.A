<?php 
require_once('init.php');
/**
* The admin class
* have priviliges to add/edit/remove any object in the databases
*/
class Admin extends User {

	function __construct() {
		global $session;
		$session->adminLock();
	}

	public function create(){
		global $connection;
		$sql = "INSERT INTO `login_info` (`id`, `username`, `password`, `email`, `type`) VALUES ('{$_POST['id']}','{$_POST['username']}','{$_POST['password']}','{$_POST['email']}','{$_POST['type']}')";
		$stmt = $connection->prepare($sql);
		if($stmt->execute()){
			$sql = "INSERT INTO students (`id`) VALUES ('{$_POST['id']}')";
			$stmt = $connection->prepare($sql);
			
			if($stmt->execute()){
				echo "Success";
			} else {
				$error = $stmt->errorInfo();
				echo "error: ".$error[2];
			}
		} else{
			$error = $stmt->errorInfo();
			echo "error: ".$error[2];
		}
	}

	public static function delete($user){
		global $connection;
		global $ProfilePicture;
		$connection->query("DELETE FROM login_info WHERE id = {$user->id} LIMIT 1");
		$connection->query("DELETE FROM students WHERE id = {$user->id} LIMIT 1");
		$ProfilePicture->id = $user->id;
		$ProfilePicture->table_name = "profile_pic";
		$ProfilePicture->delete_pic();
		return true;

	}
}

?>