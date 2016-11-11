<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/src/init.php");
$pageTitle = "Admin Control Panel";
$session->adminLock();
$admin = new Admin();

$users = $admin->getAllUsers();

$count = count($users);

$rpp = 6;
$cp = isset($_GET['cp']) ? $_GET['cp'] : 1;

$pag = new Pagination($count, $cp, $rpp);

if($cp > $pag->total_pages() || $cp <= 0){
	Redirect::redirectTo('self');
}

$offset = $pag->offset();

$users = $admin->getAllUsers($rpp, $offset);

$sec = "users";
require(ROOT_PATH . 'inc/head.php');
?>
<body>
	<div class="main">
		<div class="ui container section">
			<div class="wrapper">
				<h2>Users list</h2>
				<h3 style="margin-top:0px"><?= $count ?> total users</h3><hr><br>
				<table class="ui striped single line table" style="width:90%">
					<thead>
						<tr>
							<th></th>
							<th>ID</th>
							<th>Username</th>
							<th>Full Name</th>
							<th>Registered</th>
							<th>Last Activtiy</th>
							<th>IP Address</th>
						</tr>
					</thead>
					<tbody>
					 <?php  foreach ($users as $user): ?>
						<tr>
							<td><img src="<?= $user->img_path ?>" class="ui mini rounded image"></td>
							<td><a href=" <?= BASE_URL ?>user/<?= $user->id ?>/"><?= $user->id; ?></a></td>
							<td>@<?= $user->username; ?></td>
							<td><?= $user->full_name; ?></td>
							<td title="<?=$user->register_date?>"><?= get_timeago($user->register_date) ?></td>
							<td title="<?=$user->activity?>"><?= get_timeago($user->activity) ?></td>
							<td><?= $user->ip_address ?></td>
						</tr>
						<?php  endforeach; ?>
					</tbody>
				</table>
				<br>
				<?= $pag->display(true); ?>
			</div>
		</div>
	</div>

<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>
