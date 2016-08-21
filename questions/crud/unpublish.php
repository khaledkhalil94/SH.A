<?php 
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/src/init.php");


if (!isset($_POST['status'])) exit('Error!');
$id = sanitize_id($_POST['status']['id']);

if (!$session->is_logged_in()) exit(json_encode(array('status' => 'fail', 'id' => $id , 'msg' => 'You are not logged in!')));

$post = QNA::find_by_id($id);

if(!$post) exit(json_encode(array('status' => 'fail', 'id' => $id , 'msg' => 'Post was not found!')));

if ( USER_ID !== $post->uid ) exit(json_encode(array('status' => 'fail', 'id' => $id , 'msg' => 'You are not the comment owner!')));

if($_POST['status']['action'] == "post-unpublish") {
	if(QNA::unPublish($post->id)){
		exit(json_encode(array('status' => 'success', 'id' => $id , 'msg' => 'Post has been unpublished')));
	} else {
		exit(json_encode(array('status' => 'fail', 'id' => $id , 'msg' => 'Something went wrong!')));
	}
} elseif($_POST['status']['action'] == "post-publish"){
	if(QNA::Publish($post->id)){
		exit(json_encode(array('status' => 'success', 'id' => $id , 'msg' => 'Post has been Published')));
	} else {
		exit(json_encode(array('status' => 'fail', 'id' => $id , 'msg' => 'Something went wrong!')));
	}
}

		exit;


?>