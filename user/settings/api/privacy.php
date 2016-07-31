<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");
$session->is_logged_in() ? true : redirect_to_D("/sha/signup.php");

$id = USER_ID;

if(!$id){
	echo "User was not found!";
	redirect_to_D("/sha", 2);
}


if (!isset($_POST['submit'])) die(json_encode(array('status' => 'fail', 'msg' => 'No data received.')));

$data = $_POST;
unset($data['submit']);

if (!(current($data) == "1" || (current($data) == "0"))) die(json_encode(array('status' => 'fail', 'msg' => 'Invalid value.', 'value' => current($data))));


$query = Student::update_user_privacy($data);

if($query === true){

	die(json_encode(array('status' => 'success', 'msg' => 'Field updated.', 'name' => array_shift(array_keys($data)), 'value' => current($data))));

} else {

	die(json_encode(array('status' => 'fail', 'msg' => $query, 'name' => array_shift(array_keys($data)), 'value' => current($data))));

}
exit;


 ?>