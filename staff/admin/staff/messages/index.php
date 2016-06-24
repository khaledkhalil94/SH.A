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
		redirect_to_D(basename(__FILE__));
	}
}

$studentInfo = StudentInfo::find_by_id($id);
$session->userLock($studentInfo);
$messages = Messages::getMsgs($id);
$pageTitle = "Messages";
include (ROOT_PATH . "inc/head.php");
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
					<?php foreach ($messages as $message): 
					$staff = Staff::find_by_id($message->sender_id) ? true : false;
					$sender = $staff ? Professor::find_by_id($message->sender_id) : Student::find_by_id($message->sender_id);
					$img_path = $ProfilePicture->get_profile_pic($sender);
					$date = displayDate($message->date);
					$time = get_timeago($message->date);
					$subject = $message->subject;
					if (strlen($subject) > 100) $subject = substr($subject, 0, 102)."...";
					$title = empty($message->title) ? "[Untitled]" : $message->title;
					?>
					<tr <?php if($staff) echo "class=\"danger\"" ?>>
						<td><div class="image"><img src="<?= $img_path ?>" style="width:55px;"></div></td>
						<td ><ul>
							<?php if (!$staff){ ?>
							<li style="list-style:none;"><a href="../<?= $sender->id ?>/"><?= ucfirst($sender->firstName); ?></a></li>
							<?php }else{ ?>
							<li style="list-style:none;"><?= ucfirst($sender->firstName); ?></li>
							<?php } ?>
							<li style="list-style:none;"><div class="time" title="<?= $date; ?>"><?= $time; ?></div></li>
						</ul></td>
						<td>
							<ul>
								<a style="color:black;text-decoration: none;" href="inbox.php?msg=<?= $message->id?>">
									<li style="list-style:none;"><b><?= $title;
									 if(!Messages::isSeen($message->id)) echo " <span class=\"label label-success\">New!</span>"; ?></b></li>
									<li style="list-style:none;"><?= $subject; ?></li>
								</a>

							</ul>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php } ?>
	</div>
</div>
</div>
<?php
include (ROOT_PATH . 'inc/footer.php');
?>