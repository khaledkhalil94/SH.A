<?php
require_once( $_SERVER["DOCUMENT_ROOT"] .'/src/init.php');

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

	// follow a user
	case 'follow':

		$userID = $_POST['id'];

		$follow = User::follow($userID);

		if($follow === true){

			die(json_encode(['status' => true]));
		} else {

			die(json_encode(['status' => false, 'err' => $follow]));
		}

		break;

	// unfollow a user
	case 'unfollow':

		$userID = $_POST['id'];

		$unfollow = User::unfollow($userID);

		if($unfollow === true){

			die(json_encode(['status' => true]));
		} else {

			die(json_encode(['status' => false]));
		}

		break;

	// get user profile card
	case 'profile_card':

		$uid = $_POST['id'];

		die(View::userCard($uid));

		break;

	case 'feed_post':

		$id = $_GET['id'];

		die(View::getFeedPost($id));
		break;

	case 'feed':

		$data = $_POST;
		unset($data['action']);

		$user_id = $data['user_id'] ?? USER_ID;
		$content = $data['content'];
		$token = $data['token'];
		$now = getNow();

		// check token validation
		if(!Token::validateToken($token)){
			 die(json_encode(['status' => false, 'err' => 'Token is not valid.']));
		}

		$database = new Database();

		$data = ['user_id' => $user_id, 'content' => $content, 'poster_id' => USER_ID, 'date' => $now];
		$insert = $database->insert_data(TABLE_ACTIVITY, $data);

		if($insert === true){
			$id = $database->lastId;

			die(json_encode(['status' => true, 'id' => $id]));
		}

	case 'get_post':

		$id = sanitize_id($_GET['id']);

		$post = new Post();
		$comment = $post->get_post($id);

		if(is_object($comment)){

			die(json_encode($comment));

		} else {

			die(json_encode(['status' => false, 'err' => $comment]));
		}
		break;


	default:
		break;
}
