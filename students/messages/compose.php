<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");
if (!isset($_GET['to'])) exit("404");
$user_id = $_GET['to'];
$selfId=$session->user_id;

$pageTitle = "Send a message";
include (ROOT_PATH . "inc/head.php");

if (isset($_POST['submit'])) {
	if (!empty(trim($_POST['subject']))) {
		Messages::sendMsg();
	} else {
		echo "Subject cannot be empty.";
	}

}
$messages = Messages::getSenderMsgs($selfId, $user_id);
$staff = StaffInfo::find_by_id($user_id) ? true : false;
if ($staff) exit("You can't send a message to this account.");

?>

<div class="main">
	<div class="container">
		<div class="form">
			<form action="compose.php?to=<?= $user_id ?>" method="POST">
				<div class="form-group">
					<label for="title">Title</label>
					<input type="text" class="form-control" name="title" value="" />
				</div>
				<div class="form-group">
					<label for="subject">Subject</label>
					<textarea type="text" class="form-control" name="subject" value="" /></textarea >
					<input type="hidden" name="user_id" value="<?= $user_id; ?>" />
					<input type="hidden" name="sender_id" value="<?= $selfId; ?>" />
				</div>
				<br>
				<br>
				<!-- <input type="hidden" name="token" value="" /> -->
				<input class="btn btn-success" type="submit" name="submit" value="Send" />
				<a class="btn btn-default" href="<?= "."; ?>" role="button">Cancel</a>

			</form>
		</div>
			<?php foreach($messages as $message):
				$sender = $staff ? Professor::find_by_id($user_id) : Student::find_by_id($message->sender_id);
				$self = $selfId == $sender->id ? true : false;
				$img_path = $ProfilePicture->get_profile_pic($sender);
				$date = strtotime($message->date);
				$date = (date('Y',$date)) == date('Y') ? date('j M h:ia',$date) : date('j M Y, h:ia',$date);
?>
				<div class="details row">
				<hr>
				<div class="col-md-2">
				<a href="<?= "../".$sender->id ?>/"><img src="<?= $img_path ?>" style="width:105px;"></a>
				</div>
				<div class="col-md-10">
				<div class="time"><p><?php echo $self ? "You" : $sender->firstName; ?> sent at <?= $date; ?></p></div>
				<p><?= $message->subject; ?></p>
				</div>
				</div>
			<?php endforeach; ?>

	</div>
</div>

<?php
include (ROOT_PATH . 'inc/footer.php');
?>
