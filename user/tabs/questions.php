<?php require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/init.php");

$pub = "<i class=\"circle green icon\"></i>";
$unpub = "<i class=\"circle yellow icon\"></i>";


if($_POST['self'] == 'true'){
	require('q_self.php');
} else {
	$UserID = $_POST['uid'];
	require('q_user.php');
}
?>