<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");
$session->is_logged_in() ? true : redirect_to_D("/sha/signup.php");

if (isset($_GET['pm'])) {
	require_once('convo.php');
	exit;
}
if (isset($_GET["dl"])) {
	$msgid = $_GET["dl"];
	if(Messages::deleteMsg(USER_ID, $msgid)){
		redirect_to_D(basename(__FILE__));
	}
}

$messages = Messages::getMsgs(USER_ID);
$ids = array();
$messagez = array();
foreach ($messages as $message) {
	if(!in_array($message->sender_id, $ids)){
		$ids[] = $message->sender_id;
		$messagez[] = array_shift(Messages::getConvo(USER_ID, $message->sender_id, 1));

	}
}
$i=0;

// echo "<pre>";
// print_r($messagez);
// echo "</pre>";
// echo $ids[1];
// exit;
$pageTitle = "Messages";
include_once (ROOT_PATH . "inc/head.php");
?>
<div class="content">
	<?= msgs(); ?>
	<div class="jumbotron">
		<div class="container">
			<h2><b>Inbox</b></h2>
			<?php if (empty($messages)) {
				echo "You don't have any messages.";
			}else { ?>
			<table class="table table-hover">
				<thead>
					<tr>
						<th class="col-md-0"></th>
						<th class="col-md-3"></th>
						<th class="col-md-10"></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($messagez as $message):
					// echo "<pre>";
					// print_r($message);
					// echo "</pre>";
					// echo $ids[1];
					// exit;
					$senderID = $ids[$i];
					$sender = Student::find_by_id($senderID);
					$staff = ($message->type == "staff" || $message->type == "admin") ? true : false;
					$date = displayDate($message->date);
					$time = get_timeago($message->date);
					$subject = $message->subject;
					if (strlen($subject) > 100) $subject = substr($subject, 0, 102)."...";
					$title = empty($message->title) ? "" : $message->title;
					?>
					<tr <?php if($staff) echo "class=\"danger\"" ?>>
						<td><div class="image"><img src="<?= $message->img_path; ?>" style="width:55px;"></div></td>
						<td ><ul>
							<?php if (!$staff){ ?>
							<li style="list-style:none;"><a href="/sha/user/<?= $senderID ?>/"><?= $message->u_fullname; ?></a></li>
							<?php }else{ ?>
							<li style="list-style:none;">Adminstration</li>
							<?php } ?>
							<li style="list-style:none;"><div class="time" title="<?= $date; ?>"><?= $time; ?></div></li>
						</ul></td>
						<td>
							<ul>
								<a style="color:black;text-decoration: none;" href="?pm=<?= $senderID?>">
									<li style="list-style:none;"><b><?= $title;
									 if((!Messages::isSeen($message->id)) && $message->sender_id !== USER_ID) echo " <span class=\"label label-success\">New!</span>"; ?></b></li>
									<li style="list-style:none;"><?php if($message->sender_id === USER_ID){?>
										<i style="color: grey; font-size: small;" class="fa fa-reply" aria-hidden="true"></i><?php } ?> <?= $subject; ?>
									</li>
								</a>

							</ul>
						</td>
					</tr>
					<?php $i++; endforeach; ?>
				</tbody>
			</table>
			<?php } ?>
	</div>
</div>
</div>
<?php
include_once (ROOT_PATH . 'inc/footer.php');
?>