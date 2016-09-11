<?php 
	require_once($_SERVER["DOCUMENT_ROOT"]."/sha/src/init.php");

$sec = isset($sec) ? $sec : '';

if(USER_ID){
	$msgCount = Messages::getMsgsCount();
	$newMsg = NULL;
	if($msgCount > 0){
		$newMsg = "<div style='margin-top:2px;' class='ui green label'>{$msgCount}</div>";
	}

	$user = new User();
	$user = $user->get_user_info(USER_ID);
}
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

		<a class="item <?= $sec == 'questions' ? 'active' : null; ?>" href="<?= BASE_URL ?>questions">Questions</a>

	<?php if($session->is_logged_in()): ?>
		<a class="item <?= $sec == 'messages' ? 'active' : null; ?>" href="<?= BASE_URL ?>messages">Inbox<?= $newMsg; ?></a>
	<?php endif; ?>  

	<?php if(!$session->is_logged_in()): ?>
		<a class="item <?= $sec == 'login' ? 'active' : null; ?>" href="<?= BASE_URL ?>login.php">Log In</a>
		<a class="item <?= $sec == 'signup' ? 'active' : null; ?>" href="<?= BASE_URL ?>signup.php">Sign Up</a>
	<?php endif; ?>

	<?php if($session->is_logged_in()): ?>
		<a class="item" href="<?= BASE_URL ?>logout.php">Log out</a>
	<?php endif; ?>

	<?php if($session->is_logged_in() && $session->adminCheck()): ?>
		<a class="item <?= $sec == 'staff' ? 'active' : null; ?>" href="<?= BASE_URL ?>staff/admin" style="color:red">Admin Control Panel</a>
	<?php endif; ?>
	
	</div>
<?php if($session->is_logged_in()): ?>
	<div class="ui compact selection dropdown">
		<img class="ui avatar image" src="<?= $user->img_path ?>">
		 <div class="text default"><?= $user->full_name ?></div>
		<i class="dropdown icon"></i>
		<div class="menu">
			<a href="<?= USER_URL ?>" class="item">Profile</a>
			<a href="<?= BASE_URL."user/settings/" ?>" class="item">Settings</a>
			<a href="<?= BASE_URL ?>logout.php" class="item">Log out</a>
		</div>
	</div>
			
<?php endif; ?>
</div>

