<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/src/init.php");
$pageTitle = "Admin Control Panel";

$session->adminLock();

$admin = new Admin();
$QNA = new QNA();

$total_reports = count($admin->getAllUnqReports());
$users_count = count($admin->getAllUsers());
$qs_count = QNA::get_questions_count();

$qs = $QNA->get_questions(5, 0, true);
$users = $admin->getAllUsers(6, 0);

$sections = QNA::get_sections();

$sec = "staff";
include (ROOT_PATH . 'inc/head.php');

?>

<body>
	<div class="main" id="admincp">
		<div class="ui container section">
			<h2>Admin Control Panel</h2><br>
			<div class="ui center aligned grid" id="main_counters">
				<div class="four wide column">
					<div class="ui segment">
						<div class="label">
							<i class="ui icon users blue"></i>Total Users
						</div>
						<div class="value">
							<a href="users.php"><?= $users_count ?></a>
						</div>
					</div> 
				</div>
				<div class="four wide column">
					<div class="ui segment">
						<div class="label">
							<i class="ui icon file text green"></i> Total Questions
						</div>
						<div class="value">
							<a href="<?= BASE_URL ?>questions"><?= $qs_count ?></a>
						</div>
					</div>
				</div>
				<div class="four wide column">
					<div class="ui segment">
						<div class="label">
							<i class="ui line chart icon brown"></i> Unique visitors
						</div>
						<div class="value">
							1
						</div>
					</div>
				</div>
				<div class="four wide column">
					<div class="ui segment">
						<div class="label">
							<i class="ui warning sign icon red"></i> Reports
						</div>
						<div class="value">
							<?= $total_reports; ?>
						</div>
					</div>
				</div>
			</div>
			<br><hr><br>

			<div class="ui grid">
				<div class="eight wide column">
					<div class="ui segment green cp_segs l_qs">
						<div class="seg_title">
							<h3>Recently asked questions</h3>
						</div>
						<hr>
						<div class="ui relaxed divided list">
						<?php foreach($qs AS $q): ?>
							<div class="item">
								<img class="ui avatar image" src="<?= $q->img_path ?>">
								<div class="content">
									<a href="<?= BASE_URL.'questions/question.php?id='.$q->id ?>" class="header"><h4><?= $q->title ?></h4></a>
									<div class="time">By <a href="<?= BASE_URL.'user/'.$q->uid ?>/"><?= $q->username ?></a> - <?= get_timeago($q->created) ?></div>
								</div>
							</div>
						<?php endforeach; ?>
						</div>
						<hr>
						<div class="seg_btm">
							<a href="<?= BASE_URL ?>questions">View All</a>
						</div>
					</div>
				</div>
				<div class="eight wide column">
					<div class="ui segment red cp_segs l_users">
						<div class="seg_title">
							<h3>Latest registered users</h3>
						</div>
						<hr>
						<div class="ui centered grid">
						<?php foreach($users AS $u): ?>
							<div class="five wide column">
								<div class="thumbnail">
									<a href="<?= BASE_URL.'user/'.$u->id ?>/"><img src="<?= $u->img_path ?>"></a>
								</div>
								<div class="content">
									<a href="<?= BASE_URL.'user/'.$u->id ?>/" class="header"><?= $u->username ?></a>
									<div class="time"><?= get_timeago($u->register_date) ?></div>
								</div>
							</div>
						<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="ui grid">
				<div class="eight wide column">
					<div class="ui segment purple cp_segs sections">
						<div class="seg_title">
							<h3>Sections</h3>
						</div>
						<hr>
						<div class="ui relaxed divided list">
							<?php foreach($sections AS $sec): 
								$sec_count = QNA::get_questions_count($sec['id']); 
							?>
							<div class="item">
								<div class="content">
								<a href="<?= BASE_URL.'questions/?section='.$sec['acronym'] ?>" class="header"><h4><?= $sec['title'] ?></h4></a>
									<p><?= $sec_count ?><?= ($sec_count == 1) ? ' Question' : ' Questions' ?></p>
								</div>
							</div>
						<?php endforeach; ?>
						</div>
						<hr>
						<div class="seg_btm">
							<a href="sections.php">Manage Sections</a>
						</div>
					</div>
				</div>
				<div class="eight wide column">
					<div class="ui blue segment cp_segs db_info">
						<div class="seg_title">
							<h3>Info</h3>
						</div>
						<hr>
						<div>
							<p><b>PHP Version:</b> <?= phpversion(); ?></p>
							<p><b>HTTP Status:</b> <?= http_response_code(); ?></p>
						</div><hr>
						<div>
							<p><b>Connection Status:</b> <?= $database->status ? "<i class='ui circle icon green'></i>Connected" : $database->errors; ?></p>
							<p><b>Database Version:</b> <?= $connection->getAttribute(PDO::ATTR_SERVER_VERSION) ?></p>
							<p><b>Connection info:</b> <?= $connection->getAttribute(PDO::ATTR_CONNECTION_STATUS) ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>