 <?php 
require_once($_SERVER["DOCUMENT_ROOT"] .'/sha/src/init.php');


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
		$this->props = $this->props['values'];

		// check token validation
		if(!Token::validateToken($this->props['auth_token'])){
			$this->error = true;
			$this->errMsg = "Token is not valid.";
			return false;
		}

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
		$pw_match = self::password_check($username, $password);

		// passwords don't match or username doesn't exist
		if(!is_object($user) || !$pw_match){
			$this->error = true;
			$this->errMsg = "Username or password is incorrect.";
			return false;
		}

		if($this->error) return false; 

		// success, log the user in
		$session->login($user);

		return true;
	}


	/**
	* gets user details from the database by username or email or id
	*
	* @return object:user details | null
	*/
	public static function getUserDetails($input){
		global $connection;

		// if the input is email
		if(filter_var($input, FILTER_VALIDATE_EMAIL)){
			$inputType = "email";

		// if the input is id
		} elseif(is_numeric($input)) {
			$inputType = "id";
		} else {

		// input is username
			$inputType = "username";
		}

		$sql = "SELECT * FROM  ". TABLE_INFO ." WHERE {$inputType} = :input";
		$stmt = $connection->prepare($sql);

		$stmt->bindValue(':input', $input, PDO::PARAM_STR);

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
		global $session;

		$database = new Database();
		
		$data = $this->processData();

		if(!is_array($data) || $this->error){
			echo json_encode(['errors' =>  $this->errMsg]);
			exit;
		}

		// generate a unique id
		$id = mt_rand(0,99).substr(time(), 4);
		$data['id'] = $id;

		$data_Org = $data;
		$data_Org['ual'] = 1;

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
	* @param array $data (default null)
	*
	* @return array:user-data | false
	*
	*/
	public function processData($data=null){

		$data = $data ?: $this->props['values'];

		$cpvr = $this->verifyCaptcha($this->props['reCaptcha']);

		if(!$cpvr){
			$this->error = true;
			$this->errMsg['reCaptcha'] = "Invalid reCaptcha";
		}

		// trim and lower case all the data fields
		$data_p = array_map('strtolower',  array_map('trim', $data));

		// check for empty or already registered fields
		foreach ($data_p as $k => $v) {

			if($k == 'repassword') continue;

			if($v == ''){
				$this->error = true;
				$this->errMsg[$k] = "{$k} can't be empty";
				
			}

			if($k == 'password') continue;
			
			if(!$this->form_check($k, $v)){
				$this->error = true;
				$this->errMsg[$k] = "{$k} is already taken";
				
			}
		}

		if($this->error) return false;

		$un = $data_p['username']; $em = $data_p['email']; $pw1 = $data_p['password']; $pw2 = $data_p['repassword'];

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

		// validate email
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
	 * verify reCaptcha token
	 *
	 * @param string $resp
	 *
	 * @return boolean
	 */
	private function verifyCaptcha($resp){

		$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.RECAP_SKEY.'&response='.$resp);
		$responseData = json_decode($verifyResponse);

		return $responseData->success;
	}

	/**
	* Checks the database with field and value
	*
	* @param string field name
	* @param string field value
	* @param string database name 
	*
	* @return boolean true if values doesn't exist | false if it exists
	*
	*/
	public static function form_check($field, $value, $db='login_info'){
		global $connection;		



		$sql = "SELECT 1 FROM `{$db}` WHERE $field = '$value'";

		$exists = $connection->query($sql)->fetch();

		return $exists ? false : true;
	}


	public static function password_check($input, $pw){

		if(empty($input) || empty($pw)){
			die("Data is invalid");
		}

		$user = self::getUserDetails($input);

		if(!is_object($user)){
			die("User was not found!");
		}

		$current_pw = $user->password;


		return password_verify($pw, $current_pw);
	}
}
?>