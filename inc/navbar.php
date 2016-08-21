<?php 
	require_once($_SERVER["DOCUMENT_ROOT"]."/sha/src/init.php");

$sec = isset($sec) ? $sec : '';
?>

<div class="ui stackable menu">
	<div class="menu elements">


		<a class="item <?= $sec == 'index' ? 'active' : null; ?>" href="<?= BASE_URL; ?>">Home</a>

	<?php if($session->is_logged_in()): ?>
		<a class="item <?= $sec == 'profile' ? 'active' : null; ?>" href="<?= BASE_URL."user/".USER_ID ?>/">Profile</a>
	<?php endif; ?> 

	<?php if($session->is_logged_in() && $session->adminCheck()): ?>
		<a class="item <?= $sec == 'users' ? 'active' : null; ?>" href="<?= BASE_URL ?>staff/admin/users/students.php">Users</a>
		<a class="item <?= $sec == 'ui' ? 'active' : null; ?>" href="<?= BASE_URL ?>staff/admin/staff/professors.php">Staff</a>
	<?php endif ?>

		<a class="item <?= $sec == 'pages' ? 'active' : null; ?>" href="<?= BASE_URL ?>pages">News</a>

		<a class="item <?= $sec == 'questions' ? 'active' : null; ?>" href="<?= BASE_URL ?>questions">Questions</a>

	<?php if($session->is_logged_in()): ?>
		<a class="item <?= $sec == 'messages' ? 'active' : null; ?>" href="<?= BASE_URL ?>messages">Inbox<?= " <span class=\"label label-danger\"></span>";  ?></a>
	<?php endif; ?>  

	<?php if(!$session->is_logged_in()): ?>
		<a class="item <?= $sec == 'login' ? 'active' : null; ?>" href="<?= BASE_URL ?>login.php">Log In</a>
		<a class="item <?= $sec == 'signup' ? 'active' : null; ?>" href="<?= BASE_URL ?>signup.php">Sign Up</a>
	<?php endif; ?>

	<?php if($session->is_logged_in()): ?>
		<a class="item" href="<?= BASE_URL ?>logout.php">Log Out</a>
	<?php endif; ?>

	<?php if($session->is_logged_in() && $session->adminCheck()): ?>
		<a class="item <?= $sec == 'staff' ? 'active' : null; ?>" href="<?= BASE_URL ?>staff/admin" style="color:red">Admin Control Panel</a>
	<?php endif; ?>

	</div>
</div>
