<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/src/init.php");
$pageTitle = "Admin Control Panel";
$session->adminLock();

$qord = NULL;
$cord = NULL;

if(isset($_GET['qord']) && ($_GET['qord'] == 'count')) $qord = 'COUNT(*) DESC';
elseif(isset($_GET['cord']) && ($_GET['cord'] == 'count')) $cord = 'COUNT(*) DESC';

$q_reports = QNA::get_reports($qord, '', true);
$c_reports = Comment::get_reports($cord, '', true);
//printX($q_reports);
$sec = "staff";


include (ROOT_PATH . 'inc/head.php');
?>

<body>
	<div class="main" id="admincp">
		<div class="ui container section sec_mng">
			<h2>Reports</h2>
			<h3>Reported Questions (<?= count($q_reports); ?>)</h3>
			<table class="ui red table">
				<thead>
					<tr>
						<th>Post</th>
						<th>Owner</th>
						<th><a href="reports.php?qord=count">Reports count</a></th>
						<th><a href="reports.php?qord=date">Last reported</a></th>
						<th>Details</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($q_reports as $rp): 
					$count = QNA::get_reports_count($rp->id); ?>
					<tr>
						<td><a href="<?= BASE_URL.'questions/question.php?id='.$rp->id ?>"><?= $rp->title; ?></a></td>
						<td><a href="<?= BASE_URL.'user/'.$rp->uid ?>/"><?= $rp->fullname ?></a></td>
						<td><?= $count ?></td>
						<td><?= get_timeago($rp->date) ?></td>
						<td><a href="report.php?id=<?= $rp->id ?>">View Details</a></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<h3>Reported Comments (<?= count($c_reports); ?>)</h3>
			<table class="ui red table">
				<thead>
					<tr>
						<th>Comment</th>
						<th>Owner</th>
						<th><a href="reports.php?cord=count">Reports count</a></th>
						<th><a href="reports.php?cord=date">Last reported</a></th>
						<th>Details</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($c_reports as $rp): 
					$count = QNA::get_reports_count($rp->id); ?>
					<tr>
						<td><a href="/sha/questions/question.php?id=<?= $rp->id ?>"><?= $rp->content; ?></a></td>
						<td><a href="<?= BASE_URL.'user/'.$rp->uid ?>/"><?= $rp->fullname ?></a></td>
						<td><?= $count ?></td>
						<td><?= get_timeago($rp->date) ?></td>
						<td><a href="report.php?id=<?= $rp->id ?>">View Details</a></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>
