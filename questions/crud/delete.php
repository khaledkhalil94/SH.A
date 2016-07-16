<?php 
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/classes/init.php");
unset($_SESSION['po']);
unset($_SESSION['msg']);
$_SESSION['po'] = $_POST;

if (!isset($_POST['data'])) $session->message("Page was not found!", "/sha/templates/404.php", "danger");

 $comment = new Comment();
 $comment->id = $_POST['data']['id'];

echo json_encode($comment->deleteComment());

//Comment::delete(Comment::find_by_id($_GET['dlc']));

?>