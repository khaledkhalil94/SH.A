<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");

if(!$session->is_logged_in()) Redirect::redirectTo("/sha/signup.php");

if(!isset($_GET['msg'])) Redirect::redirectTo("../messages");

$msgid = sanitize_id($_GET['msg']);

$message = Messages::getMsg($msgid);

if(!$message) Redirect::redirectTo('');

$pageTitle = "Messages";

if(!$message) $session->message("Message was not found", "../messages", "warning");
Messages::msgSeen(USER_ID, $msgid);

$self = USER_ID == $message->u_id ? true : false;
$arch = $message->deleted == 1 ? true : false;
$staff = $message->ual == 1 ? true : false;
$receiver = Student::find_by_id($message->user_id);
$img_path = $message->img_path;
$date = displayDate($message->date);
$timeAgo = get_timeAgo($message->date);
include (ROOT_PATH . "inc/head.php");
?>
<div class="container section messages">
	<?= msgs(); ?>

<?php 
	if($self){
		require('inc/self.php');
	} else {
		require('inc/user.php');
	}
?>

<script>$('.ui.dropdown').dropdown();</script>

</div>
<?php
include (ROOT_PATH . 'inc/footer.php');
?>