<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");
$user_id = sanitize_id($_GET['pm']);

$user = $student->get_user_info($user_id);

$staff = $user->ual == 1 ? true : false;
if (USER_ID == $user_id) $session->message("Why are you viewing messages with yourself ?!", ".", "info");

$pageTitle = "Conversations";
include_once (ROOT_PATH . "inc/head.php");
$messages = Messages::getConvo(USER_ID, $user_id);
?>

<div class="main">
	<div class="container">
	<?php BackBtn(); ?>
	<?php if($staff){ ?>
		<h3>Messages from the adminstration.</h3>
	<?php } else { ?>
		<h3>Your messages with <a href="/sha/user/<?= $user_id; ?>/"><?= $user->full_name; ?></a></h3>
	<?php } ?>
		<?php if(empty($messages)) {
				echo "There are no messages between you and ".Student::find_by_id($user_id)->full_name()." yet.<br><br>";
				echo "<a class=\"btn btn-success\" href=\"compose.php?to=$user_id\">Compose a new message</a>";
			}
			echo "<a class=\"btn btn-success\" href=\"compose.php?to=$user_id\">Reply</a>";
			foreach($messages as $message):
				$sender = $student->get_user_info($message->sender_id);
				$self = USER_ID == $sender->id ? true : false;
				if (!$self) Messages::msgSeen($message->user_id, $message->id);
				$img_path = $sender->img_path;
				$date = displayDate($message->date);
				$timeAgo = get_timeAgo($message->date);

?>
				<div class="details row">
				<hr>
				<div class="col-md-2">
				<a href="<?= "../".$sender->id ?>/"><img src="<?= $img_path ?>" style="width:105px;"></a>
				</div>
				<div class="col-md-10">
				<div class="time" title="<?= $date; ?>">
				<?php if($staff){ ?>
				<p><?= $sender->firstName; ?>
				<?php } else { ?>
				<p><?= $self ? "You" : "<a href=\"/sha/user/$sender->id/\">$sender->firstName</a>"; ?>
				<?php } ?>
				 sent </a><a href="message.php?msg=<?= $message->id; ?>"><?= $timeAgo; ?></a></p></div>
				<p><?= $message->subject; ?></p>
				<a class="btn btn-danger" style="float:right;" href="?dl=<?= $message->id ?>">Delete</a>
				</div>
				</div>
		<?php endforeach; ?>

	</div>
</div>

<?php
include_once (ROOT_PATH . 'inc/footer.php');
?>
