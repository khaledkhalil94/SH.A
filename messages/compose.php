<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/src/init.php");
//if (!isset($_GET['to'])) exit("404");
$user_id = isset($_GET['to']) ? sanitize_id($_GET['to']) : null;
if (USER_ID == $user_id) $session->message("You can't send a message to yourself.", ".");

if (isset($_POST['submit'])) {
	if (!empty(trim($_POST['subject']))) {
		$_POST['id'] = mt_rand(10000,90000);
		Messages::sendMsg();
	} else {
		echo "Subject cannot be empty.";
	}

}

?>



<div class="ui segment">
	<form class="ui form" id="msg_compose" action="#">
		<div class="field">
			<label>Send to</label>
			<div class="ui search">
				<div class="ui icon input" id="msg_sendto">
					<input name="send_to" class="prompt" type="text" placeholder="Search by username or ID">
					<i class="search icon"></i>
				</div>
				<div class="results"></div>
			</div>
		</div>
		<div class="field">
			<label>Message</label>
			<textarea name="content" id="msg_context" rows="2"></textarea>
		</div>
		<input type="hidden" name="token" id="msg_token" value="<?= Token::generateToken(); ?>">
		<br>
		<button class="ui button green basic" type="submit">Send</button>
	</form>
</div>

