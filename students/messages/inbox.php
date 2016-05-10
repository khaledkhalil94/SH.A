<?php
require_once ("../../classes/init.php");
$session->is_logged_in() ? true : redirect_to_D("/sha/signup.php");
$id = $session->user_id;
if(!$id){
	echo "User was not found!";
	redirect_to_D("/sha", 20);
}

if (isset($_GET["dl"])) {
	$msgid = $_GET["dl"];
	if(Messages::deleteMsg($msgid)){
		$session->message("Message has been deleted successfully", "../messages");
	}
}
if (isset($_GET["un"])) {
	if(Messages::msgUnSee($_GET["un"])){
		redirect_to_D("../messages");
		exit;
	}
}


$msgid = $_GET['msg'];
$studentInfo = StudentInfo::find_by_id($id);
$session->userLock($studentInfo);
$pageTitle = "Messages";

$message = Messages::getMsg($msgid);
Messages::msgSeen($msgid);
$staff = StaffInfo::find_by_id($message->sender_id) ? true : false;
$sender = $staff ? Professor::find_by_id($message->sender_id) : Student::find_by_id($message->sender_id);
$img_path = $ProfilePicture->get_profile_pic($sender);
$date = strtotime($message->date);
$date = (date('Y',$date)) == date('Y') ? date('j M h:ia',$date) : date('j M Y, h:ia',$date);
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
						<hr>
						<p><?= $message->subject; ?></p>
						<hr>
						<a class="btn btn-info" href="../messages">Back</a>&nbsp;
						<?php if(!$staff): ?>
						<a class="btn btn-success" href="compose.php?to=<?= $sender->id; ?>">Reply</a>
						<a class="btn btn-default" href="?un=<?= $msgid ?>">Mark unseen</a>&nbsp;
						<a class="btn btn-default" href="">Report</a>
						<a class="btn btn-danger" href="?dl=<?= $msgid ?>">Delete message</a>
						<?php endif; ?>

					</div>
				</div>


			</div>

		</div>

	</div>
</div>
<?php
include (ROOT_PATH . 'inc/footer.php');
?>