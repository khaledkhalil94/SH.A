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
	case 'new_comment':

		$insert = Comment::new_comment($data);

		if(is_int($insert)){
			
			die(json_encode(['status' => true, 'id' => $insert]));

		} else {

			die(json_encode(['status' => false, 'err' => $insert[2]]));
		}
		break;

	case 'get_comment':

		$id = sanitize_id($data['id']);

		$comment = Comment::getComment($id);

		if(is_array($comment)){
			
			die(json_encode($comment));

		} else {

			die(json_encode(['status' => false, 'err' => $comment]));
		}
		break;
	
	case 'upvote':

		$PostID =sanitize_id($data['comment_id']);

		// check if comment exists
		if(!Comment::getComment($PostID)){
			die(json_encode(['status' => false, 'err' => 'Comment was not found.']));
		}

		// check if user has already upvoted the comment
		$voted = QNA::has_voted($PostID, USER_ID);
		if($voted){
			die(json_encode(['status' => false, 'err' => 'You have already upvoted this comment.']));
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

		$PostID = sanitize_id($data['comment_id']);

		// check if comment exists
		if(!Comment::getComment($PostID)){
			die(json_encode(['status' => false, 'err' => 'Comment was not found.']));
		}

		// check if user has not upvoted the comment
		$voted = QNA::has_voted($PostID, USER_ID);
		if(!$voted){
			die(json_encode(['status' => false, 'err' => 'Can\'t downvote this comment, try again later.']));
		}

		$remove = QNA::downvote($PostID, USER_ID);

		if($remove === true){
			die(json_encode(['status' => true]));
		}
		break;

	case 'delete':

		$CommentID = sanitize_id($data['id']);

		$comment = Comment::getComment($CommentID);

		if(!is_array($comment)) die(json_encode(['status' => false, 'id' => $CommentID, 'err' => 'Comment was not found.']));

		if(USER_ID !== $comment['uid']) die(json_encode(['status' => false, 'id' => $CommentID, 'err' => 'Authentication error.']));

		$delete = Comment::deleteComment($CommentID);

		if($delete === true){
			 die(json_encode(['status' => true, 'id' => $CommentID]));
		} else {
			 die(json_encode(['status' => false, 'id' => $CommentID, 'err' => 'Unknown error.']));
		}
		
		break;

	case 'edit':
		$CommentID = sanitize_id($data['id']);
		$content = $data['content'];

		$comment = Comment::getComment($CommentID);

		if(!is_array($comment)) die(json_encode(['status' => false, 'id' => $CommentID, 'err' => 'Comment was not found.']));

		if(USER_ID !== $comment['uid']) die(json_encode(['status' => false, 'id' => $CommentID, 'err' => 'Authentication error.']));

		$update = Comment::edit_comment($CommentID, $content);

		if($update === true){
			die(json_encode(['status' => true, 'id' => $CommentID]));
		} else {
			die(json_encode(['status' => false, 'id' => $CommentID, 'err' => $update]));
		}
		break;

	case 'report':
		$PostID = sanitize_id($data['post_id']);
		$content = $data['content'];
		$user_id = USER_ID;

		if($data['type'] == 'post'){

			$post = QNA::get_question($PostID);

			if(!is_object($post)) die(json_encode(['status' => false, 'id' => $PostID, 'err' => 'Post was not found.']));

			$report = QNA::report($PostID, $content, $user_id);

			if($report === true){
				die(json_encode(['status' => true, 'id' => $PostID]));
			} else {

				if($report[1] == 1062) {

					die(json_encode(['status' => false, 'id' => $PostID, 'err' => 1062]));
				} else {
					
					die(json_encode(['status' => false, 'id' => $PostID, 'err' => $report[2]]));
				}
			}
		} else {

			$comment = Comment::getComment($PostID);

			if(!is_array($comment)) die(json_encode(['status' => false, 'id' => $PostID, 'err' => 'Comment was not found.']));

			$report = QNA::report($PostID, $content, $user_id);

			if($report === true){
				die(json_encode(['status' => true, 'id' => $PostID]));
			} else {

				if($report[1] == 1062) {

					die(json_encode(['status' => false, 'id' => $PostID, 'err' => 1062]));
				} else {
					
					die(json_encode(['status' => false, 'id' => $PostID, 'err' => $report[2]]));
				}
			}
		}
		break;
	default:
		# code...
		break;
}


















