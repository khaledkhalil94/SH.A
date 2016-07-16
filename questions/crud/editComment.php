<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/classes/init.php");
//include (ROOT_PATH . 'inc/head.php');
//$session->adminLock();
//$id = isset($_GET['id']) ? $_GET['id'] : null;

$_POST = $_POST['data'];
if($_POST['action'] == "edit"){
	if($post = Comment::find_by_id($_POST['id'])){
		$content = trim($_POST['content']);

		if (empty($content)) exit("Content is empty!");

		if ($content == $post->content) exit("Content didn't change!");

		if ($comment->update($_POST)){
			$edit_date = date("Y-m-d H:i:s");
			Comment::query("UPDATE `comments` SET last_modified = '$edit_date' WHERE id = {$post->id}");
			echo json_encode(array('status'=>'success','edit_date'=> $edit_date));
			exit;
		} else {
			echo $comment->update($_POST);
			exit();
		}

	}
}

?>

