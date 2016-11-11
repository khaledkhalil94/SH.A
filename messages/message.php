<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/src/init.php");

if(!$session->is_logged_in()) Redirect::redirectTo("/signup.php");

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
$img_path = $message->img_path;
$date = $message->date;
$sec = "messages";
include (ROOT_PATH . "inc/head.php");
?>
<div class="ui container section messages">
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