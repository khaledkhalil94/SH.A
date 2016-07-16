<?php 
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/classes/init.php");
// TO BE IMPROVED
if (!isset($_POST['comment'])) $session->message("Page was not found!", "/sha/templates/404.php", "danger");


$comment = new Comment();
$comment = $comment->comment();
echo json_encode($comment->getComment());
exit;

	
?>