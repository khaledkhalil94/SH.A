<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/src/init.php");
if(!$session->is_logged_in()) Redirect::redirectTo("/sha/signup.php");

if (isset($_GET['msg'])) {
	require_once('message.php');
	exit;
}

if (isset($_GET['pm'])) {
	require_once('convo.php');
	exit;
}

$sh = isset($_GET['sh']) ? $_GET['sh'] : 'inb';
$pageTitle = "Messages";
$sec = "messages";
include_once (ROOT_PATH . "inc/head.php");
?>
<div class="ui container section messages">
	<?= msgs(); ?>
	<ul class="msg-center center" id="msgs-msg" style="display:none;">

	</ul>
	<div class="ui grid">
		<div class="four wide column">
			<div class="ui vertical menu">
				<a class="item <?= $sh == 'compose' ? 'teal active' : null; ?>" href="?sh=compose">
					Send a new message
				</a>
				<a class="item <?= $sh == 'inb' ? 'teal active' : null; ?>" href="?sh=inb">
					Inbox
				</a>
				<a class="item <?= $sh == 'st' ? 'teal active' : null; ?>" href="?sh=st">
					Sent
				</a>
				<a class="item <?= $sh == 'tr' ? 'teal active' : null; ?>" href="?sh=tr">
					Archive
				</a>
				<a class="item <?= $sh == 'blc' ? 'teal active' : null; ?>" href="?sh=blc">
					Blocked
				</a>
			</div>
		</div>
		<div class="twelve wide column">
			<?php
			switch ($sh) {
				case 'compose':
					require_once('compose.php');
					break;

				case 'inb':
					$messages = Messages::getMsgs(USER_ID);
					echo Messages::displayMessages($messages, 'inbox');
					break;

				case 'tr':
					$messages = Messages::getDeletedMsgs(USER_ID);
					echo Messages::displayMessages($messages, 'archive');
					break;

				case 'st':
					$messages = Messages::getSentMsgs(USER_ID);
					echo Messages::displayMessages($messages, 'sent');
					break;

				case 'blc':
					require('blocked.php');
					break;

				default:
					break;
			} ?>
		</div>
	</div>
</div>
<?php
include_once (ROOT_PATH . 'inc/footer.php');
?>