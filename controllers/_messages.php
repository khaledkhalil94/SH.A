<?php 
require_once( $_SERVER["DOCUMENT_ROOT"] .'/sha/src/init.php');

if(!$session->is_logged_in()) Redirect::redirectTo('/sha');

//Allow access only via ajax requests
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' ) {

	Redirect::redirectTo('404');
}

if(isset($_GET['un'])){

	$name = $_GET['un'];

	$users = User::users_search($name);

	$results = [];


	foreach ($users as $k => $v) {

		$users[$k]['title'] = '@'.$v['title'];
		$results[] = $users[$k];
		
	}

	die(json_encode(['results' => $results]));
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
			
			echo json_encode($action);
		}

		break;

	case 'msg_send':
		
		$data = $_POST;
		unset($_POST);
		$action = Messages::sendMsg($data);

		if($action === true){ // delete success

			echo json_encode(array('status' => '1', 'msgID' => $msg_id));

		} else {
			
			echo json_encode($action);
		}

		break;

	case 'msg_unread':
		
		$msg_id = $_POST['msgID'];
		$action = Messages::msgUnRead($msg_id);

		if($action === true){ // delete success

			echo json_encode(array('status' => '1', 'msgID' => $msg_id));

		} else {
			
			echo $action[2];
		}

		break;

	case 'msg_block':
		
		$msg_id = $_POST['msgID'];
		$message = Messages::getMsg($msg_id);

		$userID = $message->sender_id;

		$action = User::block($userID);

		if($action === true){ // delete success

			echo json_encode(array('status' => '1'));

		} else {
			echo json_encode($action);
		}

		break;

	case 'unblock':
		
		$userID = $_POST['msgID'];

		$action = User::unBlock($userID);

		if($action === true){ // delete success

			echo json_encode(array('status' => '1'));

		} else {
			
			echo $action[2];
		}

		break;

	default:
		exit;
		break;
}

