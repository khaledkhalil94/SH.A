<?php 
require_once('init.php');
class Session {

	private $logged_in=false, $level;
	public $user_id, $username, $msg, $msgType;

	function __construct(){
		session_start();
		$this->check_login();
		if(isset($this->user_id)){
			define("USER_ID", $this->user_id);
			if (USER_ID != 1 ) $this->updateActivity(USER_ID);		
		}
		// to avoid errors when not logged in
		defined('USER_ID') ? USER_ID : define('USER_ID', "");
	}

	public function is_logged_in(){
		return $this->logged_in;
	}
	
	private function check_login(){
		if(isset($_SESSION['user_id'])){
			$this->user_id = $_SESSION['user_id'];
			$this->username = $_SESSION['username'];
			$this->level = $_SESSION['level'];
			$this->logged_in = true;
		} else {
			unset($this->user_id);
			$this->logged_in = false;
		}

	}

	public function login_by_id($id){
		global $connection;
		$sql = "SELECT * FROM login_info WHERE id = {$id}";
		$stmt = $connection->prepare($sql);
		
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_OBJ);
		$this->login($user);
	}

	public function login($user){
		if($user){
			$this->user_id = $_SESSION['user_id'] = $user->id;
			$this->username = $_SESSION['username'] = $user->username;
			$this->type = $_SESSION['level'] = $user->type;
			$this->level = true;
			defined('USER_ID') ? null : define('USER_ID', $this->user_id);
		}
	}

	private function updateActivity($id){
		global $connection;
		$sql = "UPDATE `login_info` SET activity = CURRENT_TIMESTAMP WHERE id = {$id}";
		$connection->query($sql);
	}

	public function logout(){
		unset($_SESSION['user_id']);
		unset($this->user_id);
		unset($this->username);
		session_destroy();
		$this->logged_in = false;
	}

	public function message($msg, $location=null, $msgType="success"){
		if(isset($msg)){
			$_SESSION['msg'] = $msg;
			$_SESSION['msgType'] = $msgType;
		}
		redirect_to_D($location);
	}

	public function displayMsg(){
		 if(isset($_SESSION['msg'])){
		 	$this->msg = $_SESSION['msg'];
		 	$this->msgType = $_SESSION['msgType'];
		 } else {
		 	return false;
		 }
		unset($_SESSION['msg']);
		unset($_SESSION['msgType']);
		$msgInfo = array();
		$msgInfo["msg"] = $this->msg;
		$msgInfo["msgType"] = $this->msgType;
		return $msgInfo;
	}

	public function getLevel(){
		return $this->level;
	}

	public function adminLock(){
		if($this->is_logged_in() && $this->username == "admin" && $this->user_id == "1"){
			return true;
		} else {
			exit("You must be an admin to review this page.");
			return false;
		}
	}
	public function adminCheck(){
		if($this->is_logged_in() && $this->username == "admin" && $this->user_id == "1"){
			return true;
		} else {
			return false;
		}
	}

	public function userLock($user){
		if($user){
			if($this->is_logged_in() && $this->user_id === $user->id){
				return true;
			} elseif($this->adminCheck()){
				return true;
			} else {
				echo ("You can't view this page.");
				redirect_to_D("/sha", 2);
				return false;
			}
		} elseif ($this->adminCheck()){
			return true;
		} else {
			echo ("User was not found.");
			redirect_to_D("/sha", 2);
		}
	}

	public function userCheck($user){
		if($user){
			if($this->is_logged_in() && $this->user_id === $user->id){
				return true;
			} elseif($this->adminCheck()){
				return true;
			} else {
				return false;
			}
		}
	}

}

$session = new Session();

?>

