<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");

//sleep(1);
if(isset($_POST['form_check'])){
	$_POST = $_POST['form_check'];

	if($_POST['form'] == 'signup'){

		$field = $_POST['name'];
		$value = $_POST['value'];

		$exists = User::check_field($field, $value);

		if($exists) {
			echo json_encode(array('status' =>'false', 'field' => $field));
		} else {
			echo json_encode(array('status' =>'true', 'field' => $field));
		}
	}

}
 ?>
