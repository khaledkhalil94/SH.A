<?php 

class Session {

	private $logged_in=false;
	private $level;
	public $user_id;
	public $username;
	public $msg;


	function __construct(){
		session_start();
		$this->check_login();
		if(isset($this->user_id)){
			define("USER_ID", $this->user_id);
		}

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
		}

	}

	public function logout(){
		unset($_SESSION['user_id']);
		unset($this->user_id);
		unset($this->username);
		session_destroy();
		$this->logged_in = false;
	}

	public function message($msg, $location=null){
		if(isset($msg)){
			$_SESSION['msg'] = $msg;
		}
		redirect_to_D($location);
	}

	public function displayMsg(){
		 if(isset($_SESSION['msg'])){
		 	$this->msg = $_SESSION['msg'];
		 } else {
		 	return false;
		 }
		unset($_SESSION['msg']);
		return $this->msg;
	}

	public function getLevel(){
		return $this->level;
	}

	public function adminLock(){
		if($this->is_logged_in() && $this->username == "admin" && $this->user_id == "1"){
			return true;
		} else {
			exit("You must be an admin to review this page.");
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
			}
		} else {
			echo ("User was not found.");
			redirect_to_D("/sha", 2);
		}
	}

}

$session = new Session();

?>

