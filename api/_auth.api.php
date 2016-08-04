<?php 
require_once('../classes/init.php');

/**
* handles authentication ajax calls for different forms
*/
Class _api {
	
	public $error=false, $errMsg=[];
	private $props, $action;


	public function __construct() {

		// if there's no post data
		if (!isset($_POST['action'])) redirect_to_D('/404.php');

		$this->props = $_POST;
		$this->action = $this->props['action'];
		unset($_POST);


		switch ($this->action) {
			case 'signup_form_check':

				$field = $this->props['name'];
				$value = $this->props['value'];

				$r = $this->form_check($field, $value);

				if($r){

					echo json_encode(array('status' =>'true', 'field' => $field, 'value' => $value));

				} else {

					echo json_encode(array('status' =>'false', 'field' => $field, 'value' => $value));
				}

				break;

			case 'signup':
				$create = $this->createNewAccount();
				if(!is_object($create) || $this->error){
					echo (count($this->errMsg) == 1) ? array_shift($this->errMsg) : json_encode($this->errMsg);
				}
				break;
			
			default:
				# code...
				break;
		}
	}


	/**
	* Checks the database with field and value
	*
	* @param string => field name
	* @param string => field value
	*
	* @return boolear
	*
	*/
	private function form_check($field, $value){
		global $connection;		

		$sql = "SELECT 1 FROM `login_info` WHERE $field = '$value'";

		$exists = $connection->query($sql)->fetch();

		return $exists ? false : true;
	}


	/**
	* Register a new user
	*
	* @return object|false
	*
	*/
	private function createNewAccount(){
		global $connection, $session, $database;

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
		}

		if($pw1 != $pw2){
			$this->error = true;
			$this->errMsg['password'] = "Passwords don't match.";
		}

		if($this->error) return false;

		unset($data['repassword']);

		// generate a unique id
		$id = mt_rand(0,99).substr(time(), 4);
		$data['id'] = $id;
		$data = (object)$data;


		// all is good to go, insert new rows into the database
		// create row in the user_info database
		$create_user_row = $database->insert_data('login_info', $data);
		if($create_user_row !== true){
			$this->error = true;
			$this->errMsg[] = $create_user_row;
			return false;
		}

		// create row in the users database
		$data = (object)['id' => $id];
		$create_user_row = $database->insert_data('students', $data);
		if($create_user_row !== true){
			$this->error = true;
			$this->errMsg[] = $create_user_row;
			return false;
		}

		// create row in the privacy db
		$data = (object)['user_id' => $id];
		$create_user_pp = $database->insert_data('user_privacy', $data);
		if($create_user_pp !== true){
			$this->error = true;
			$this->errMsg[] = $create_user_pp;
			return false;
		}
		// create user folder
		if(!file_exists(DEF_IMG_UP_DIR.USER_ID. DS )){
			if(mkdir(DEF_IMG_UP_DIR. DS .USER_ID)){
				
				// create an index file and redirect to 404 page
				$path = DEF_IMG_UP_DIR. DS .USER_ID. DS ;
				$fp = fopen($path . "/index.php", "w");
				fwrite($fp, "<?php header(\"Location: /sha/404.php\"); ?>");
				fclose($fp);

			}
		}


		return $data;

	}


}

$api = new _api();











