<?php 
require_once( $_SERVER["DOCUMENT_ROOT"] .'/sha/src/init.php');

if(!$session->is_logged_in()) Redirect::redirectTo('/sha');

// Allow access only via ajax requests
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' ) {

	Redirect::redirectTo('404');
}


if(isset($_POST['action'])){

	$action = $_POST['action'];
	unset($_POST['action']);
	$data = $_POST;

} elseif(isset($_GET['action'])){

	$action = $_GET['action'];
	unset($_GET['action']);
	$data = $_GET;
} else {

	die("Error! bad request.");
}


switch ($action) {
	case 'create':
		$title = $data['title'];
		$content = $data['content'];
		$section = $data['section'];
		$token = $data['token'];

		if(empty($title) || empty($content) || $section == 0){

			die(json_encode(['status' => false, 'err' => 'data is not valid']));
		}

		if(!Token::validateToken($token)){

			die(json_encode(['status' => false, 'err' => 'Token is not valid']));
		}

		unset($data['token']);

		$create = $QNA->create($data);

		if(is_int($create)){

			die(json_encode(['status' => true, 'id' => $create]));
		} else {

			die(json_encode(['status' => false, 'err' => $create]));
		}
		break;

	case 'upvote':
		$PostID = sanitize_id($data['id']);

		// check if question exists
		if(!is_object(QNA::get_question($PostID))){
			die(json_encode(['status' => false, 'err' => 'Question was not found.']));
		}

		// check if user has already upvoted the question
		$voted = QNA::has_voted($PostID, USER_ID);
		if($voted){
			die(json_encode(['status' => false, 'err' => 'You have already upvoted this Question.']));
		}

		global $database;

		$data = ['post_id' => $PostID, 'user_id' => USER_ID];
		$insert = $database->insert_data(TABLE_POINTS, $data);

		if($insert === true){
			die(json_encode(['status' => true]));
		} else {
			die(json_encode(['status' => false, 'err' => $database->errors[2]]));
		}
		break;

	case 'downvote':

		$PostID = sanitize_id($data['id']);

		// check if question exists
		if(!is_object(QNA::get_question($PostID))){
			die(json_encode(['status' => false, 'err' => 'Question was not found.']));
		}

		// check if user has not upvoted the question
		$voted = QNA::has_voted($PostID, USER_ID);
		if(!$voted){
			die(json_encode(['status' => false, 'err' => 'You haven\'t upvoted this question.']));
		}

		$remove = QNA::downvote($PostID, USER_ID);

		if($remove === true){
			die(json_encode(['status' => true]));
		}
		break;

	case 'publish':

		$PostID = sanitize_id($data['id']);

		// check if question exists
		$question = QNA::get_question($PostID);
		if(!is_object($question)){
			die(json_encode(['status' => false, 'err' => 'Question was not found.']));
		}

		if(USER_ID !== $question->uid) die(json_encode(['status' => false, 'id' => $PostID, 'err' => 'Authentication error.']));

		$Pub = QNA::Publish($PostID);

		if($Pub === true){
			die(json_encode(['status' => true]));
		} else {
			die(json_encode(['status' => false, 'err' => $Pub]));
		}
		break;

	case 'unPublish':

		$PostID = sanitize_id($data['id']);

		// check if question exists
		$question = QNA::get_question($PostID);
		if(!is_object($question)){
			die(json_encode(['status' => false, 'err' => 'Question was not found.']));
		}

		if(USER_ID !== $question->uid) die(json_encode(['status' => false, 'id' => $PostID, 'err' => 'Authentication error.']));

		$Pub = QNA::unPublish($PostID);

		if($Pub === true){
			die(json_encode(['status' => true]));
		} else {
			die(json_encode(['status' => false, 'err' => $Pub]));
		}
		break;

	case 'delete':

		$PostID = sanitize_id($data['id']);

		// check if question exists
		$question = QNA::get_question($PostID);
		if(!is_object($question)){
			die(json_encode(['status' => false, 'err' => 'Question was not found.']));
		}

		if(USER_ID !== $question->uid) die(json_encode(['status' => false, 'id' => $PostID, 'err' => 'Authentication error.']));

		$QNA = new QNA($PostID);
		
		$delete = $QNA->delete();

		if($delete === true){
			die(json_encode(['status' => true]));
		}
		break;

	case 'edit':

		$PostID = sanitize_id($data['id']);
		$content = $data['content'];

		// check if question exists
		$question = QNA::get_question($PostID);
		if(!is_object($question)){
			die(json_encode(['status' => false, 'err' => 'Question was not found.']));
		}

		if(USER_ID !== $question->uid) die(json_encode(['status' => false, 'id' => $PostID, 'err' => 'Authentication error.']));

		$QNA = new QNA($PostID);
		$edit = $QNA->edit_question($content);

		if($edit === true){
			die(json_encode(['status' => true]));
		}

		break;
}

