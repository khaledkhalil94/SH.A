<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/src/init.php");
//include (ROOT_PATH . 'inc/head.php');
//$session->adminLock();
//$id = isset($_GET['id']) ? $_GET['id'] : null;

if(!isset($_POST['data'])) exit(json_encode(array('status'=>'fail','comment_id' => $_POST['id'], 'msg' => 'No data was received.')));

$_POST = $_POST['data'];

if($_POST['user_id'] !== USER_ID) exit(json_encode(array('status'=>'fail','post_id' => $_POST['id'], 'msg' => 'Authentication failed.')));

switch ($_POST['action']) {
	case 'comment-edit':
		if($post = Comment::find_by_id($_POST['id'])){
			$content = trim($_POST['content']);

			if (empty($content)) exit(json_encode(array('status'=>'fail','comment_id' => $_POST['id'], 'msg' => 'Content is empty.')));

			if ($comment->update($_POST)){
				$edit_date = date("Y-m-d H:i:s");
				Comment::query("UPDATE `comments` SET last_modified = '$edit_date' WHERE id = {$post->id}");
				echo json_encode(array('status'=>'success','edit_date'=> $edit_date));
				exit;
			} else {
				echo json_encode(array('status'=>'fail','comment_id' => $_POST['id'], 'msg' => $comment->update($_POST)));
				exit();
			}

		} else {
			exit(json_encode(array('status'=>'fail','comment_id' => $_POST['id'], 'msg' => 'Comment was not found.')));
		}
	break;

	case 'post-edit':
		if(!($post = QNA::find_by_id($_POST['id']))) exit(json_encode(array('status'=>'fail','post_id' => $_POST['id'], 'msg' => 'Post was not found.')));

		if($post->uid !== USER_ID) {
			echo(json_encode(array('status'=>'fail','post_id' => $_POST['id'], 'msg' => 'Authentication failed.')));
			exit;
		}

		if (empty(trim($_POST['content']))) {
			echo(json_encode(array('status'=>'fail','post_id' => $_POST['id'], 'msg' => 'Content is empty.')));
			exit;
		}

		if ($QNA->update($_POST)){
			$edit_date = date("Y-m-d H:i:s");
			QNA::query("UPDATE `questions` SET last_modified = '$edit_date' WHERE id = {$_POST['id']}");
			echo json_encode(array('status'=>'success', 'post_id' => $_POST['id'], 'edit_date'=> $edit_date));
			exit;
		} else {
			echo json_encode(array('status'=>'fail','post_id' => $_POST['id'], 'msg' => $QNA->update($_POST)));
			exit();
		}

	break;
	
	default:
		 exit(json_encode(array('status'=>'fail','post_id' => $_POST['id'], 'msg' => 'Nothing was received.')));
	break;
}

?>

