<?php 
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/src/init.php");
// TO BE IMPROVED
if (!isset($_POST['vote'])) $session->message("Page was not found!", "/sha/templates/404.php", "danger");


switch ($_POST['vote']['action']) {

	case 'post-upvote':
		echo QNA::upvote(QNA::find_by_id($_POST['vote']['id']), USER_ID);
		exit;
		break;

	case 'post-downvote':
		echo QNA::downvote(QNA::find_by_id($_POST['vote']['id']), USER_ID);
		exit;
		break;

	case 'comment-upvote':
		echo QNA::upvote(Comment::find_by_id($_POST['vote']['id']), USER_ID);
		exit;
		break;

	case 'comment-downvote':
		echo QNA::downvote(Comment::find_by_id($_POST['vote']['id']), USER_ID);
		exit;
		break;
	
	default:
		exit("error");
		break;
}

?>