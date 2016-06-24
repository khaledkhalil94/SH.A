<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");
$session->is_logged_in() ? true : redirect_to_D("/sha/signup.php");
$user_id = $session->user_id;
if(!$user_id){
	echo "User was not found!";
	redirect_to_D("/sha", 20);
}

if (isset($_GET["dl"])) {
	$msgid = $_GET["dl"];
	if(Messages::deleteMsg($user_id, $msgid)){
		$session->message("Message has been deleted successfully", "../messages");
	} else {
		$session->message("Message not found", "../messages");
	}
}

if (isset($_GET["un"])) {
	$msgid = $_GET["un"];
	if(Messages::msgUnSee($user_id, $msgid)){
		redirect_to_D("../messages");
		exit;
	}
}

if (isset($_GET["report"])) {
	$msgid = $_GET["report"];
	if (Messages::Report($msgid)) $session->message("Message has been reported to the admin.", "../messages");
}

if(!isset($_GET['msg'])) redirect_to_D("../messages");

$msgid = $_GET['msg'];
$studentInfo = StudentInfo::find_by_id($user_id);
$session->userLock($studentInfo);
$pageTitle = "Messages";

$message = Messages::getMsg($user_id, $msgid);
if(!$message) $session->message("Message not found", "../messages");
Messages::msgSeen($user_id, $msgid);
$staff = Staff::find_by_id($message->sender_id) ? true : false;
$sender = $staff ? Professor::find_by_id($message->sender_id) : Student::find_by_id($message->sender_id);
$img_path = $ProfilePicture->get_profile_pic($sender);
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
				<a class="btn btn-info" href="../messages">Back</a>&nbsp;
				<?php if(!$staff): ?>
					<a class="btn btn-success" href="compose.php?to=<?= $sender->id; ?>">Reply</a>
					<a class="btn btn-default" href="?un=<?= $msgid ?>">Mark unseen</a>&nbsp;
					<a class="btn btn-default" href="?report=<?= $msgid ?>">Report</a>
					<a class="btn btn-danger" href="?dl=<?= $msgid ?>">Delete message</a>
				<?php endif; ?>
			</div>


		</div>

	</div>
</div>
<?php
include (ROOT_PATH . 'inc/footer.php');
?>