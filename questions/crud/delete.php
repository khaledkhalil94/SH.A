<?php 
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/classes/init.php");


if (!isset($_POST['delete'])) $session->message("Page was not found!", "/sha/templates/404.php", "danger");

switch ($_POST['delete']['action']) {

	case 'comment-delete':
		
		 $comment = new Comment();
		 $comment->id = $_POST['delete']['id'];

		echo json_encode($comment->deleteComment());
		exit;
		break;

	case 'post-delete':
		if (!$session->is_logged_in()) exit(json_encode(array('status' => 'fail', 'id' => $_POST['delete']['id'], 'msg' => 'You are not logged in!')));
			
		$post = QNA::find_by_id($_POST['delete']['id']);

		if(!$post) exit(json_encode(array('status' => 'fail', 'id' => $_POST['delete']['id'], 'msg' => 'Post was not found!')));

		if ( USER_ID !== $post->uid ) exit(json_encode(array('status' => 'fail', 'id' => $_POST['delete']['id'], 'msg' => 'You are not the comment owner!')));

		if(QNA::delete($post)){
			exit(json_encode(array('status' => 'success', 'id' => $_POST['delete']['id'], 'msg' => 'Post has been deleted')));
		} else {
			exit(json_encode(array('status' => 'fail', 'id' => $_POST['delete']['id'], 'msg' => 'Something went wrong!')));
		}

		exit;
		break;
	
	default:
		exit("error");
		break;
}

?>