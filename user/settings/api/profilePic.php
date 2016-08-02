<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");

// delete action
if(isset($_POST['action']) && $_POST['action'] == 'delete') {

	$image = new Images();

	if($image->delete_profile_pic()){

		die(json_encode(array('status' => 'success', 'path' => DEF_PIC)));

	} else {

		die(json_encode(array('status' => 'success', 'errMsg' => $image->errMsg)));

	}
		

}


// upload or change
if(!isset($_FILES['file'])) header('Location: /sha/404.php');

$_FILES = $_FILES['file'];

$image = new Images();

if(Images::has_pic(USER_ID)){
	if($image->change_profile_pic()){
		echo json_encode(array('action' => 'update', 'status' => 'success', 'path' => $image->path, 'id' => $image->id));
		exit;

	} else {
		
		echo json_encode(array('status' => 'fail', 'errMsg' => $image->errMsg, 'id' => $image->id));
		exit;


	}
} else {
	if($image->upload_profile_pic()){
		echo json_encode(array('action' => 'upload', 'status' => 'success', 'path' => $image->path, 'id' => $image->id));
		exit;

	} else {
		
		echo json_encode(array('status' => 'fail', 'errMsg' => $image->errMsg, 'id' => $image->id));
		exit;


	}
}

?>