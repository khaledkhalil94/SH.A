<?php require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/src/init.php");

$pub = "<i class=\"circle green icon\"></i>";
$unpub = "<i class=\"circle yellow icon\"></i>";


if($_GET['self'] == 'true'){
	require('q_self.php');
} else {
	$UserID = $_GET['uid'];
	require('q_user.php');
}
?>

