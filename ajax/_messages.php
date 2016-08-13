<?php 
require_once( $_SERVER["DOCUMENT_ROOT"] .'/sha/classes/init.php');

if(!$session->is_logged_in()) Redirect::redirectTo('/sha');

//Allow access only via ajax requests
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' ) {

	Redirect::redirectTo('404');
}

switch ($_POST['action']) {

	case 'msg_hide':
		
		$msg_id = $_POST['msgID'];

		$action = Messages::hideMsg($msg_id);

		if($action === true){ // delete success

			echo json_encode(array('status' => '1', 'msgID' => $msg_id));

		} else {
			
			echo $action[2];
		}

		break;

	case 'msg_unhide':
		
		$msg_id = $_POST['msgID'];

		$action = Messages::unHideMsg($msg_id);

		if($action === true){ // delete success

			echo json_encode(array('status' => '1', 'msgID' => $msg_id));

		} else {
			
			echo $action[2];
		}

		break;

	case 'msg_remove':
		
		$msg_id = $_POST['msgID'];

		$action = Messages::deleteMsg($msg_id);

		if($action === true){ // delete success

			echo json_encode(array('status' => '1', 'msgID' => $msg_id));

		} else {
			
			echo $action[2];
		}

		break;

	default:
		exit;
		break;
}
