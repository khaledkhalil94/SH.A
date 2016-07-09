<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");
//if (!isset($_GET['to'])) exit("404");
$user_id = $_GET['to'];
if (USER_ID == $_GET['to']) $session->message("You can't send a message to yourself.", ".");

$pageTitle = "Send a message";
include_once (ROOT_PATH . "inc/head.php");

if (isset($_POST['submit'])) {
	if (!empty(trim($_POST['subject']))) {
		$_POST['id'] = mt_rand(10000,90000);
		Messages::sendMsg();
	} else {
		echo "Subject cannot be empty.";
	}

}
$messages = Messages::getConvo(USER_ID, $user_id);
$staff = Staff::find_by_id($user_id) ? true : false;
if ($staff) exit("You can't send a message to this account.");

?>

<div class="main">
	<div class="container">
		<div class="form">
			<form action="compose.php?to=<?= $user_id ?>" method="POST">
				<div class="form-group">
					<label for="title">Sending to</label>
					<input class="form-control" id="disabledInput" type="text" value="<?= Student::find_by_id($user_id)->full_name(); ?>" disabled>
				</div>
				<div class="form-group">
					<label for="title">Title</label>
					<input type="text" class="form-control" name="title" value="" />
				</div>
				<div class="form-group">
					<label for="subject">Subject</label>
					<textarea type="text" class="form-control" name="subject" value="" /></textarea >
					<input type="hidden" name="user_id" value="<?= $user_id; ?>" />
					<input type="hidden" name="sender_id" value="<?= USER_ID; ?>" />
				</div>
				<br>
				<br>
				<!-- <input type="hidden" name="token" value="" /> -->
				<input class="btn btn-success" type="submit" name="submit" value="Send" />
				<a class="btn btn-default" href="<?= "."; ?>" role="button">Cancel</a>

			</form>
		</div>
	</div>
</div>

<?php
include (ROOT_PATH . 'inc/footer.php');
?>
