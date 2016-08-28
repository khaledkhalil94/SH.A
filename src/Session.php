<?php 
require_once($_SERVER["DOCUMENT_ROOT"] .'/sha/src/init.php');

class Session {

	/**
	 * @var boolean $logged_in logged in status
	 */
	private $logged_in=false;

	/**
	 * @var int $user_id
	 */
	public $user_id;

	/**
	 * @var int $ual user access level
	 */
	public $ual;

	/**
	 * @var sting $username
	 */
	public $username;

	/**
	 * @var string $msg message stored in session
	 */
	public $msg;

	/**
	 * @var string $msgType type of the message(succes, error..)
	 */
	public $msgType;


	function __construct(){

		session_start();
		$this->check_login();

		if($this->logged_in){

			// define user_id constant
			define("USER_ID", $this->user_id);

			// update user activity
			if (USER_ID != 1 ) $this->updateActivity(USER_ID);
		}

		// to avoid errors when not logged in
		defined('USER_ID') ? USER_ID : define('USER_ID', "");

	}

	/**
	 * checks if logged in or not
	 *
	 * @return boolean
	 */
	public function is_logged_in(){
		return $this->logged_in;
	}
	
	private function check_login(){
		if(isset($_SESSION['user_id'])){
			$this->user_id = $_SESSION['user_id'];
			$this->username = $_SESSION['username'];
			$this->logged_in = true;
		}

	}

	/**
	 * writes logged user details into the session file
	 *	
	 * @param object: user detals
	 *
	 * @return boolean
	 */
	public function login($user){

		// if already logged in
		if ($this->logged_in) return false;

		// if data is not an object
		if(!is_object($user)) return false;

		// if data is empty
		if(empty($user->id)) return false;

		// generate a new SID
		session_regenerate_id(true);

		// write into the session file
		$this->user_id = $_SESSION['user_id'] = $user->id;
		$this->username = $_SESSION['username'] = $user->username;
		$this->ual = $_SESSION['ual'] = $user->ual;
		$this->logged_in = true;

		defined('USER_ID') ? null : define('USER_ID', $this->user_id);
		//echo $_SESSION['username']; exit;
		return true;

	}

	/**
	 * boow guess what happens here ?
	 */
	public function logout(){

		unset($_SESSION['user_id']);
		unset($this);

		session_destroy();

		$this->logged_in = false;
	}

	/**
	 * sets a key and value in the session
	 *
	 * @param mixed $key
	 * @param mixed $value
	 */
	public static function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}

	/**
	 * gets a value from the session by key
	 *
	 * @param mixed $key
	 *
	 * @return mixed $value | boolean false (if doesn't exist)
	 */
	public static function get($key)
	{
		if (isset($_SESSION[$key])) {
			return $_SESSION[$key];
		} else {
			return false;
		}
	}

	
	/**
	 * updates user activity field in the database
	 *
	 * @param int user_id
	 */
	private function updateActivity($user_id){
		global $connection;

		$sql = "UPDATE `login_info` SET activity = CURRENT_TIMESTAMP WHERE id = {$user_id}";
		$connection->query($sql);
	}

	public function message($msg, $location=null, $msgType="success"){
		if(isset($msg)){
			$_SESSION['msg'] = $msg;
			$_SESSION['msgType'] = $msgType;
		}
		Redirect::redirectTo($location);
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
				Redirect::redirectTo("/sha", 2);
				return false;
			}
		} elseif ($this->adminCheck()){
			return true;
		} else {
			echo ("User was not found.");
			Redirect::redirectTo("/sha", 2);
		}
	}

	public function userCheck($user){
		if(is_object($user)){
			if($this->is_logged_in() && $this->user_id === $user->id){
				return true;
			} elseif($this->adminCheck()){
				return true;
			} else {
				return false;
			}
		} elseif(is_numeric($user)){
			if($this->is_logged_in() && $this->user_id === $user){
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

