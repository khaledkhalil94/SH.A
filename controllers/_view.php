<?php 
require_once( $_SERVER["DOCUMENT_ROOT"] .'/sha/src/init.php');

//Allow access only via ajax requests
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' ) {

	Redirect::redirectTo('404');
}

if(isset($_POST['action'])){

	$action = $_POST['action'];
	unset($_POST['action']);


} elseif(isset($_GET['action'])){

	$action = $_GET['action'];
	unset($_GET['action']);

} else {

	die("Error! bad request.");
}

switch ($action) {

	// get user profile card
	case 'profile_card':

		$uid = $_POST['id'];

		die(View::userCard($uid));

		break;

	case 'renderComment':

		$id = $_GET['id'];

		die(View::renderComment($id));

		break;

	default:
		break;
}