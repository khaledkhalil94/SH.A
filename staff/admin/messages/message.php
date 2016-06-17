<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");
$session->is_logged_in() ? true : redirect_to_D("/sha/signup.php");
$user_id = $session->user_id;
$session->adminLock();

if (isset($_GET["dl"])) {
	$msgid = $_GET["dl"];
	if(Messages::deleteMsg($user_id, $msgid)){
		$session->message("Message has been deleted successfully", "../messages");
	} else {
		$session->message("Message not found", "../messages");
	}
}

if (isset($_GET["aprv"])) {
	$msgid = $_GET["aprv"];
	Messages::unReport($msgid);
}

if(!isset($_GET['msg'])) redirect_to_D("./reports.php");

$msgid = $_GET['msg'];
$pageTitle = "Messages";

$message = Messages::getMsgById($msgid);
$sender = Student::find_by_id($message->sender_id);
$img_path = ProfilePicture::get_profile_pic($sender);
$date = displayDate($message->date);
include (ROOT_PATH . "inc/head.php");
?>
<div class="content">
	<?= msgs(); ?>

	<div class="jumbotron">
		<div class="container">
			<div class="content">
				<div class="details row">
					<div class="col-md-3">
						<h4>Sent by <a href="../<?= $sender->id ?>/"><?= ucfirst($sender->firstName); ?></a></h4>
						<div class="time">at <?= $date; ?></div>
						<a href="<?= "../".$sender->id ?>/"><img src="<?= $img_path ?>" style="width:165px;"></a>
					</div>
					<div class="col-md-8">
						<p><b><?= $message->title; ?></b></p>
						<p style="min-height:220px; padding-top: 30px;"><?= $message->subject; ?></p>
						<hr>
					</div>
				</div>
			</div>
			<div style="text-align:center;">
				<a class="btn btn-success" href="?aprv=<?= $msgid ?>">Approve message</a>
				<a class="btn btn-danger" href="?dl=<?= $msgid ?>">Delete message</a>
				<a class="btn btn-info" href="./reports.php">Back</a>&nbsp;
			</div>


		</div>

	</div>
</div>
<?php
include (ROOT_PATH . 'inc/footer.php');
?>