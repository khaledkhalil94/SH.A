<?php 
require_once( $_SERVER["DOCUMENT_ROOT"] .'/sha/classes/init.php');

//Allow access only via ajax requests
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' ) {

	header('Location:404.php');
}


switch ($_POST['action']) {
	case 'signup':

		$auth = new Auth();
		$auth->RegisterNewUser();
		break;
		
	case 'signup_form_check':
		$name = $_POST['name'];
		$value = $_POST['value'];

		$r = Auth::form_check($name, $value);

		if($r){
			echo json_encode(array('status' =>'true', 'field' => $name, 'value' => $value));
		} else {
			echo json_encode(array('status' =>'false', 'field' => $name, 'value' => $value));
		}

		break;

	case 'login':
		$auth = new Auth();
		if($auth->login()){
			echo "1";
		} else {
			echo json_encode($auth->errMsg);
		}

	default:
		break;
}

