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

		$QNA = new QNA();
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
		if(!is_object(QNA::get_question($PostID)) && !is_array(Post::get_post($PostID, true))){
			die(json_encode(['status' => false, 'err' => 'Post was not found.']));
		}

		// check if user has already upvoted the question
		$voted = QNA::has_voted($PostID, USER_ID);
		if($voted){
			die(json_encode(['status' => false, 'err' => 'You have already upvoted this post.']));
		}

		$database = new Database();

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
		if(!is_object(QNA::get_question($PostID)) && !is_array(Post::get_post($PostID, true))){
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
		$title = $data['title'];

		// check if question exists
		$question = QNA::get_question($PostID);
		if(!is_object($question)){
			die(json_encode(['status' => false, 'err' => 'Question was not found.']));
		}

		if(USER_ID !== $question->uid && !$session->adminCheck()) die(json_encode(['status' => false, 'id' => $PostID, 'err' => 'Authentication error.']));

		$errors = [];

		$QNA = new QNA($PostID);


		if($title != $question->title){

			if(trim($title) == '') die(json_encode(['err' => 'Title can\'t be empty']));
			$edit = $QNA->edit_title($title);

			if($edit !== true){
				$errors[] = $edit;
			}
		}

		if($content != $question->content){
			$edit = $QNA->edit_question($content);

			if($edit !== true){
				$errors[] = $edit;
			}
		}

		if(empty($errors)) die(json_encode(['status' => true]));
			else die(json_encode(['err' => $errors]));

		break;

	case 'save':

		$PostID = sanitize_id($data['id']);

		// check if question exists
		$question = QNA::get_question($PostID);
		if(!is_object($question)){
			die(json_encode(['status' => false, 'err' => 'Question was not found.']));
		}

		$QNA = new QNA($PostID);
		$save = $QNA->save_post();

		if($save === true){
			die(json_encode(['status' => true]));
		} else {
			die(json_encode(['status' => false, 'err' => $save]));
		}

		break;

	case 'unsave':

		$PostID = sanitize_id($data['id']);

		// check if question exists
		$question = QNA::get_question($PostID);
		if(!is_object($question)){
			die(json_encode(['status' => false, 'err' => 'Question was not found.']));
		}

		$save = QNA::remove_saved($PostID);

		if($save === true){
			die(json_encode(['status' => true]));
		} else {
			die(json_encode(['status' => false, 'err' => $save]));
		}

		break;


	case 'post_delete':

		$PostID = sanitize_id($data['id']);

		// check if post exists
		$post = Post::get_post($PostID, true);

		if(!is_array($post)){
			die(json_encode(['status' => false, 'err' => 'Post was not found.']));
		}

		if((USER_ID !== $post['user_id']) && (USER_ID !== $post['poster_id'])) die(json_encode(['status' => false, 'id' => $PostID, 'err' => 'Authentication error.']));

		$post = new Post();
		$post->PostID = $PostID;

		$delete = $post->delete();

		if($delete === true){
			die(json_encode(['status' => true]));
		}
		break;
}


