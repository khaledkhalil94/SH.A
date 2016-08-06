<?php 
require_once($_SERVER["DOCUMENT_ROOT"] .'/sha/classes/init.php');


/**
* handles authentications
*/
Class Auth {
	
	/**
	* @var boolean $error error status
	*/
	public $error=false;
	
	/**
	* @var array $errMsg array of errors
	*/
	public $errMsg=[];
	
	/**
	* @var mixed $props this properties
	*/
	private $props;
	
	/**
	* @var mixed $action this action
	*/
	private $action;


	public function __construct() {

		// if there's no post data
			$this->props = $_POST;
		if (isset($_POST['action'])) {

			$this->action = $this->props['action'];
			unset($_POST);
		}
	}


	/**
	* logs in a user
	*
	* @return boolean
	*/
	public function login(){
		global $session;

		$username = trim($this->props['username']);
		$password = trim($this->props['password']);

		if(empty($username) || empty($password)){
			$this->error = true;
			$this->errMsg = "Username or Password can't be empty.";
			return false;
		}

		$user = self::getUserDetails($username);

		if(!$user) {
			$this->error = true;
			$this->errMsg = "Username or password is incorrect.";
			return false;
		}

		// if admin login
		if($user->username == "admin"){
			// TODO: extra verifications for admin login
		}

		// match the user's password with the hashed one
		$pw_match = password_verify($password, $user->password);

		// passwords don't match or username doesn't exist
		if(!is_object($user) || !$pw_match){
			$this->error = true;
			$this->errMsg = "Username or password is incorrect.";
			return false;
		}

		if(!$this->error); // if no errors

		// success, log the user in
		return $session->login($user);
	}


	/**
	* gets user details from the database by username
	*
	* @return object:user details | null
	*/
	public static function getUserDetails($username){
		global $connection;
		$sql = "SELECT * FROM `login_info` WHERE username = :username";
		$stmt = $connection->prepare($sql);
		
		$stmt->bindValue(':username', $username, PDO::PARAM_STR);

		if(!$stmt->execute()){
			$error = $stmt->errorInfo();
			$error = $error[2];

			$this->error = true;
			$this->errMsg = $error;

		}

		return $stmt->fetch(PDO::FETCH_OBJ);
	}

	/**
	* Registers a new user
	*
	* @return json encoded array
	*
	*/
	public function RegisterNewUser(){
		global $database, $session;

		$data = $this->processData();

		if(!is_array($data) || $this->error){
			echo json_encode(['errors' =>  $this->errMsg]);
			exit;
		}

		// generate a unique id
		$id = mt_rand(0,99).substr(time(), 4);
		$data['id'] = $id;

		$data_Org = $data;

		// hashing the password
		$pw = $data_Org['password'];
		$pw_h = password_hash($pw, PASSWORD_BCRYPT);

		$data['password'] = $pw_h;

		// create row in the user_info database
		$data['ip_address'] = $_SERVER['REMOTE_ADDR'];
		$create_user_row = $database->insert_data('login_info', $data);
		if($create_user_row !== true){
			$this->error = true;
			$this->errMsg[] = $create_user_row;
			return false;
		}

		$data = ['id' => $id];
		// create row in the users database
		$create_user_row = $database->insert_data('students', $data);
		if($create_user_row !== true){
			$this->error = true;
			$this->errMsg[] = $create_user_row;
			return false;
		}

		// create row in the privacy db
		$data = ['user_id' => $id];
		$create_user_pp = $database->insert_data('user_privacy', $data);
		if($create_user_pp !== true){
			$this->error = true;
			$this->errMsg[] = $create_user_pp;
			return false;
		}

		// create user folder
		if(!file_exists(DEF_IMG_UP_DIR.$id. DS )){
			if(mkdir(DEF_IMG_UP_DIR. DS .$id)){
				
				// create an index file that redirects to 404 page
				$path = DEF_IMG_UP_DIR. DS .$id. DS ;
				$fp = fopen($path . "/index.php", "w");
				fwrite($fp, "<?php header(\"Location: /sha/404.php\"); ?>");
				fclose($fp);

			}
		}

		echo json_encode(['data' => $data_Org]);
		
		// login user
		$session->login((object)$data_Org);
	}


	/**
	* Process and validate data from a signup form
	*
	* @return array:user-data | false
	*
	*/
	private function processData(){

		$data = $this->props['values'];
				

		// trim and lower case all the data fields
		$data_p = array_map('strtolower',  array_map('trim', $data));
		$un = $data_p['username']; $em = $data_p['email']; $pw1 = $data_p['password']; $pw2 = $data_p['repassword'];

		// check for empty or already registered fields
		foreach ($data_p as $k => $v) {

			if($v == ''){
			if($k == 'repassword') continue;
				$this->error = true;
				$this->errMsg[$k] = "{$k} can't be empty";
				
			}

			if($k == 'password') continue;
			if($k == 'repassword') continue;
			if(!$this->form_check($k, $v)){
				$this->error = true;
				$this->errMsg[$k] = "{$k} is already taken";
				
			}
		}

		// check unsername length
		if (strlen($un) > 15){
			$this->error = true;
			$this->errMsg['username'] = "Username must be between 4 and 15 characters.";
			
		} elseif(strlen($un) < 4){
			$this->error = true;
			$this->errMsg['username'] = "Username must be between 4 and 15 characters.";

		}

		// check username allowed characters
		if(preg_match('/[^a-z_\-0-9]/i', $un)){
			$this->error = true;
			$this->errMsg['username'] = "Username may only contain alphanumeric characters or '_'";
		}

		// check username allowed characters
		if(!filter_var($em, FILTER_VALIDATE_EMAIL)){
			$this->error = true;
			$this->errMsg['email'] = "email is not valid";
		}

		// check password length
		if (strlen($pw1) < 4){
			$this->error = true;
			$this->errMsg['password'] = "Password must be at least 4 characters long.";
			return false;
		}

		if($pw1 != $pw2){
			$this->error = true;
			$this->errMsg['password'] = "Passwords don't match.";
		}

		if($this->error) return false;


		unset($data['repassword']);
		return $data;

	}


	/**
	* Checks the database with field and value
	*
	* @param string => field name
	* @param string => field value
	* @param string (optional) => database name
	*
	* @return boolean
	*
	*/
	public static function form_check($field, $value, $db='login_info'){
		global $connection;		



		$sql = "SELECT 1 FROM `{$db}` WHERE $field = '$value'";

		$exists = $connection->query($sql)->fetch();

		return $exists ? false : true;
	}


}






