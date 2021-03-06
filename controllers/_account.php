<?php
require_once( $_SERVER["DOCUMENT_ROOT"] .'/src/init.php');

if(!$session->is_logged_in()) Redirect::redirectTo('/sha');

//Allow access only via ajax requests
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' ) {

	Redirect::redirectTo('404');
}

// generate a new SID to avoid session fixation
session_regenerate_id(true);

switch ($_POST['action']) {

	// user account deletion
	case 'delete_acc':

		$token = $_POST['token'];
		$pw = $_POST['password'];

		$user = new User();
		$delete = $user->deleteUser($token, USER_ID, $pw);
		if($delete === true){ // delete success

			$session->logout();
			echo "1";

		} else {

			echo json_encode($user->errors);
		}

		break;

	// update user profile info
	case 'update_info':

		$database = new Database();

		$data = $_POST['values'];
		unset($_POST);

		// check token validation
		if(!Token::validateToken($data['auth_token'])){
			 die(json_encode("Token is not valid."));
		}
		unset($data['auth_token']);

		// check maximum length
		foreach ($data as $k => $v) {

			if(strlen($v) > 30) die(json_encode('Input is too long.'));
		}

		$fields = array_keys($data);
		$values = array_values($data);

		$update = $database->update_data("students", $fields, $values, "id", USER_ID);
		if($update === true){ // delete success

			echo "1";

		} else {

			echo json_encode($database->errors);
		}

		break;

	// update user settings
	case 'update_settings':

		$database = new Database();

		$data = $_POST['values'];

		$user = new User();
		$update = $user->changeSettings($data);

		if($update === true) {
			echo "1";
		} else {
			echo json_encode($user->errors);
		}
		exit;

	break;


	// update user privacy
	case 'privacy_update':

		$database = new Database();

		unset($_POST['action']);
		$data = $_POST;
		unset($_POST);

		$fields = $data['fields'];
		$values = $data['values'];

		$update = $database->update_data("user_privacy", $fields, $values, "user_id");
		if($update === true){ // update success

			echo "1";

		} else {

			echo json_encode($database->errors);
		}

		break;

	default:
		break;
}
