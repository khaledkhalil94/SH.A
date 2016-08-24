 <?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/src/init.php");

$sec = "questions";
$session->is_logged_in() ? require(ROOT_PATH . "questions/question_user.php") : require(ROOT_PATH . "questions/question_pub.php");
?>
<script src="scripts/question.js"></script>
<script src="scripts/comment.js"></script>