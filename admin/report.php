<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/src/init.php");
$pageTitle = "Admin Control Panel";
$session->adminLock();

$id = $_GET['id'];

$report = QNA::get_reports(null, $id) ?: Comment::get_reports(null, $id);

printX($report);
$sec = "staff";

include (ROOT_PATH . 'inc/head.php');
?>

<body>
	<div class="main" id="admincp">
		<div class="ui container section sec_mng">
			<h2>Reports</h2>
			<table class="ui red table">
				<thead>
					<tr><th>Post</th>
						<th>Owner</th>
						<th>Reports count</th>
						<th>Details</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($report as $rp): 
					$count = count(Admin::getAllUnqReports($rp->id)); ?>
					<tr>
						<td><?= $rp->title; ?></td>
						<td><?= $rp->fullname ?></td>
						<td><?= $count ?></td>
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
