<?php
require_once ("src/init.php");
$session->logout();
//$msg->success('You have logged out.');
Redirect::RedirectTo();
?>