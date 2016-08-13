<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");

if(!$session->is_logged_in()) Redirect::redirectTo("/sha/signup.php");

if(!isset($_GET['msg'])) Redirect::redirectTo("../messages");

$msgid = sanitize_id($_GET['msg']);

$message = Messages::getMsg($msgid);

if ($message->user_id != USER_ID && $message->sender_id != USER_ID) {
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
		//Redirect::redirectTo("../messages");
		exit;
	}
}


$pageTitle = "Messages";

if(!$message) $session->message("Message was not found", "../messages", "warning");
Messages::msgSeen(USER_ID, $msgid);

$self = USER_ID == $message->u_id ? true : false;
$receiver = Student::find_by_id($message->user_id);
$img_path = $message->img_path;
$date = displayDate($message->date);
$timeAgo = get_timeAgo($message->date);
include (ROOT_PATH . "inc/head.php");
?>
<div class="container section messages">
	<?= msgs(); ?>

	<div class="ui container">
		<div class="ui image tiny msg-image">
			<img src="<?= $img_path ?>" style="width:165px;">
		</div>
		<div class="msg-main">
			<div class="msg-user_info">
				<?php if ($self) { ?>
					<h4>You Sent to <a href="/sha/user/<?= $message->user_id ?>/"><?= $receiver->full_name(); ?></a></h4>
				<?php } else { ?>
					<h4>Sent by <a href="/sha/user/<?= $message->u_id ?>/"><?= $message->u_fullname; ?></a></h4>
				<?php } ?>
				<div class="time" title="<?= $date; ?>"><?= $timeAgo; ?></div>
				<div title="Actions" class="ui pointing dropdown" id="msg-actions">
					<i class="setting link large icon"></i>
					<div class="menu">
						<div class="item" id="post-edit">
							<a class="ui a">Mark as unread</a>
						</div>
						<div class="item" id="post-unpublish">
							<a class="ui a" href="./?pm=<?= $message->u_id; ?>">View Conversation</a>
						</div>
						<div class="item" id="post-publish">
							<a class="ui a">Delete</a>
						</div>
						<div class="item" id="post-delete">
							<a class="ui a">Block</a>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<div class="msg-body">
				<p style="min-height:220px;"><?= $message->subject; ?></p>
			</div>
		</div>
	</div>
	<hr>
	<div>
		<a class="btn btn-info" href="../messages">Back</a>&nbsp;
		<?php if($self){ ?>
			<a class="btn btn-default" href="./?pm=<?= $receiver->id; ?>">View full conversation</a>
			<a class="btn btn-danger" href="?dl=<?= $msgid ?>">Delete message</a>
		<?php } else { ?>
			<a class="btn btn-success" href="compose.php?to=<?= $message->sener_id; ?>">Reply</a>
			<a class="btn btn-default" href="?un=<?= $msgid ?>">Mark unseen</a>
			<a class="btn btn-default" href="./?pm=<?= $message->u_id; ?>">View full conversation</a>
			<a class="btn btn-danger" href="?dl=<?= $msgid ?>">Delete message</a>
		<?php } ?>
	</div>

<script>$('.ui.dropdown').dropdown();</script>

</div>
<?php
include (ROOT_PATH . 'inc/footer.php');
?>