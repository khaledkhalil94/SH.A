<?php 
require_once ("classes/init.php");
$session->logout();
//$msg->success('You have logged out.');
header('Location:index.php');	

?>