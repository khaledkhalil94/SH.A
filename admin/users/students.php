<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/src/init.php");
$pageTitle = "Admin Control Panel";
$session->adminLock();

$users = Admin::getAllUsers();
// printX($users);
$sec = "users";
require(ROOT_PATH . 'inc/head.php');
?>
<body>
	<div class="main">
		<div class="ui container section">
			<div class="wrapper">
				<h2>Users list</h2><hr><br>
				<table class="ui very basic collapsing celled table" style="width:90%">
					<thead>
						<tr>
							<th></th>
							<th>ID</th>
							<th>Username</th>
							<th>Full Name</th>
							<th>Last Activtiy</th>
							<th>Profile</th>
						</tr>
					</thead>
					<tbody>
					 <?php  foreach ($users as $user): ?>
						<tr>
							<td><img src="<?= $user->img_path ?>" class="ui mini rounded image"></td>
							<td><?= $user->id; ?></td>
							<td>@<?= $user->username; ?></td>
							<td><?= $user->full_name; ?></td>
							<td><?= get_timeago($user->activity) ?></td>
							<td><?= "<a href=" .BASE_URL . "staff/admin/user/student.php?id=" . $user->id . ">View profile</a>"; ?></td>
						</tr>
						<?php  endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>
