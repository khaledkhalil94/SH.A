<?php 

class Session {

	private $logged_in=false;
	public $user_id;
	public $username;
	public $msg;


	function __construct(){
		session_start();
		$this->check_login();

	}

	public function is_logged_in(){
		return $this->logged_in;
	}
	private function check_login(){
		if(isset($_SESSION['user_id'])){
			$this->user_id = $_SESSION['user_id'];
			$this->username = $_SESSION['username'];
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
			$_SESSION['type'] = $user->type;
			$this->logged_in = true;
		}
	}

	public function logout(){
		unset($_SESSION['user_id']);
		unset($this->user_id);
		unset($this->username);
		session_destroy();
		$this->logged_in = false;
	}

	public function message($msg){
		if(isset($msg)){
			$this->msg = $_SESSION['msg'] = $msg;
			unset($_SESSION['msg']);
		} else {
			$this->msg = "aa";
		}
	}

	public function adminLock(){
		if($this->is_logged_in() && $this->username == "admin" && $this->user_id == "1"){
			return true;
		} else {
			echo $this->user_id;
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

}

$session = new Session();

?>

