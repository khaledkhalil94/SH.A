<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/src/init.php");
sleep(.6);
if (!isset($_POST['report'])) exit("Something went wrong!");


$id = $_POST['report']['post_id'];

$question = !empty(QNA::find_by_id($id)) ? true : false;
$comment = !empty(Comment::find_by_id($id)) ? true : false;

if (!$question && !$comment){
	exit(json_encode(array('status' => 'fail', 'errMsg' => 'Post was not found!')));
}

	if($_POST['report']['action'] == 'report-comment'){
		echo $response = $QNA->report();
		exit;

}

?>