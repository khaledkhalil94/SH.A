<?php 
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/src/init.php");
// TO BE IMPROVED
if (!isset($_POST['comment'])) $session->message("Page was not found!", "/sha/templates/404.php", "danger");


//$comment = new Comment();
$comment = $comment->insert_comment();

$newComment = $comment->getComment();

$newComment->img_path = $newComment->img_path ?: DEF_PIC;
echo json_encode($newComment);
exit;

	
?>