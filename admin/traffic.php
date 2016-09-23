<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/src/init.php");
$pageTitle = "Admin Control Panel";
$session->adminLock();

$rpp = 20;
$cp = isset($_GET['cp']) ? $_GET['cp'] : 1;

$admin = new Admin();

$count = count($admin->getTraffic());

$pag = new Pagination($count, $cp, $rpp);
$offset = $pag->offset();

$records = $admin->getTraffic($rpp, $offset);

$sec = "staff";

include (ROOT_PATH . 'inc/head.php');
?>

<body>
	<div class="main" id="admincp">
		<div class="ui container section rep_mng">
			<h2>Count (<?= $count ?>)</h2>
			<table class="ui red table">
				<thead>
					<tr>
						<th>IP</th>
						<th>Last seen</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($records as $r): ?>
					<tr>
						<td><?= $r->ip_addr ?></td>
						<td><?= get_timeago($r->date) ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?= $pag->display(true); ?>
		</div>
	</div>
</div>
<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>
