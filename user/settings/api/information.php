<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");

if (!isset($_POST['submit'])) die(json_encode(array('status' => 'fail', 'msg' => 'No data received.')));

$_POST = $_POST['submit'];

if(empty($_POST)) die(json_encode(array('status' => 'fail', 'msg' => 'No data received.')));

$_POST = $_POST['values'];


$_POST['id'] = USER_ID;

$query = $student->update();

unset($_POST['id']);

if($query === true){

	$updatedFields = array_combine( array_keys($_POST), array_values($_POST));

	die(json_encode(array('status' => 'success', 'msg' => 'Fields updated.', 'Fields updated: ' => $updatedFields, 'Number of fields updated: ' => count($_POST))));

} else {

	$updatedFields = array_combine( array_keys($_POST), array_values($_POST));

	die(json_encode(array('status' => 'fail', 'msg' => $query['errMsg'], $updatedFields)));

}


?>