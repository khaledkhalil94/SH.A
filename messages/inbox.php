<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");
$session->is_logged_in() ? true : redirect_to_D("/sha/signup.php");

if(!isset($_GET['msg'])) redirect_to_D("../messages");
$msgid = sanitize_id($_GET['msg']);
$message = Messages::getMsg($msgid)[0];

if (!($message->user_id === USER_ID || $message->sender_id === USER_ID)) {
	$session->message("Can't find message", "../messages", "info");
}

if (isset($_GET["dl"])) {
	$msgid = sanitize_id($_GET["dl"]);
	if(Messages::deleteMsg(USER_ID, $msgid)){
		$session->message("Message has been deleted successfully", "../messages", "success");
	} else {
		$session->message("Message not found", "../messages", "warning");
	}
}

if (isset($_GET["un"])) {
	$msgid = sanitize_id($_GET["un"]);
	if(Messages::msgUnSee($msgid)){
		//redirect_to_D("../messages");
		exit;
	}
}


$pageTitle = "Messages";

if(!$message) $session->message("Message was not found", "../messages", "warning");
Messages::msgSeen(USER_ID, $msgid);
$staff = !empty($message->s_id) ? true : false;

$sender = $staff ? Staff::find_by_id($message->s_id) : Student::find_by_id($message->u_id);
$self = USER_ID == $sender->id ? true : false;
$receiver = Student::find_by_id($message->user_id);
$img_path = $ProfilePicture->get_profile_pic($sender);
$date = displayDate($message->date);
$timeAgo = get_timeAgo($message->date);
include (ROOT_PATH . "inc/head.php");
?>
<div class="content">
	<?= msgs(); ?>

	<div class="jumbotron">
		<div class="container">
			<div class="content">
				<div class="details row">
					<div class="col-md-3">
					<?php if($staff){ ?>
							<h4>Sent by Adminstration</h4>
					<?php } else { ?>
						<?php if ($self) { ?>
							<h4>You Sent to <a href="/sha/user/<?= $message->user_id ?>/"><?= $receiver->full_name(); ?></a></h4>
						<?php } else { ?>
							<h4>Sent by <a href="/sha/user/<?= $message->u_id ?>/"><?= $message->u_fullname; ?></a></h4>
						<?php } ?>
					<?php } ?>
						<div class="time" title="<?= $date; ?>"><?= $timeAgo; ?></div>
						<img src="<?= $img_path ?>" style="width:165px;">
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
					<?php if($self){ ?>
						<a class="btn btn-default" href="./?pm=<?= $receiver->id; ?>">View full conversation</a>
						<a class="btn btn-danger" href="?dl=<?= $msgid ?>">Delete message</a>
					<?php } else { ?>
						<a class="btn btn-success" href="compose.php?to=<?= $sender->id; ?>">Reply</a>
						<a class="btn btn-default" href="?un=<?= $msgid ?>">Mark unseen</a>
						<a class="btn btn-default" href="./?pm=<?= $message->u_id; ?>">View full conversation</a>
						<a class="btn btn-danger" href="?dl=<?= $msgid ?>">Delete message</a>
					<?php } ?>
				<?php endif; ?>
			</div>


		</div>

	</div>
</div>
<?php
include (ROOT_PATH . 'inc/footer.php');
?>