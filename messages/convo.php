<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/src/init.php");
$user_id = sanitize_id($_GET['pm']);

$user = User::get_user_info($user_id);
if(!$user) Redirect::redirectTo('404');

$staff = $user->ual == 1 ? true : false;
if (USER_ID == $user_id) Redirect::redirectTo();

$pageTitle = "Conversations";
$sec = "messages";
include_once (ROOT_PATH . "inc/head.php");
$messages = Messages::getConvo(USER_ID, $user_id);

?>

<div class="main">
	<div class="ui container section message-list">
		<?php if($staff){ ?>
		<h3>Messages from the admin.</h3>
		<?php } else { ?>
		<h3>Your messages with <?= View::user($user_id) ?></h3>
		<?php } ?>
		<?php if(empty($messages)) {
				echo "There are no messages between you and ".$user->full_name." yet.<br><br>";
				echo "<a class=\"btn btn-success\" href=\"compose.php?to=$user_id\">Compose a new message</a>";
			}

			foreach($messages as $message):
				$sender = User::get_user_info($message->sender_id);
				$self = USER_ID == $sender->id ? true : false;
				if (!$self) Messages::msgSeen($message->user_id, $message->id);
				$img_path = $sender->img_path;
				$date = displayDate($message->date);
				$timeAgo = get_timeAgo($message->date);
				$subject = ctrim($message->subject, 120, true);
?>
				<div class="message message-row ui">
					<div class="message-image">
						<a href="<?= "../".$sender->id ?>/">
							<img class="ui tiny image" src="<?= $img_path ?>">
						</a>
					</div>
					<div class="message-details">
						<?php if($staff){ ?>
						<p><?= $sender->firstName; ?>
						<?php } else { ?>
						<p><?= $self ? "You" : "<a href=\"/sha/user/$sender->id/\">$sender->firstName</a>"; ?>
						<?php } ?>

						<a class="time" title="<?= $date; ?>" href="message.php?msg=<?= $message->id; ?>"><?= $timeAgo; ?></a></p>

						<p class="message-content"><?= $subject; ?></p>
					
					</div>
				</div>
		<?php endforeach; ?>
	</div>
</div>

<?php
include_once (ROOT_PATH . 'inc/footer.php');
?>

