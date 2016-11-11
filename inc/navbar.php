<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/src/init.php");

$sec = isset($sec) ? $sec : '';

if(USER_ID){
	$msgCount = Messages::getMsgsCount();
	$newMsg = NULL;
	if($msgCount > 0){
		$newMsg = "<div style='margin-top:2px;' class='ui green label'>{$msgCount}</div>";
	}

	$nvqu = new User(USER_ID);
	$nvqu = $nvqu->get_user_info();
}
?>

<div class="ui stackable menu">
	<div class="menu elements">


		<a class="item<?= $sec == 'index' ? ' active' : null; ?>" href="<?= BASE_URL; ?>">Home</a>

		<a class="item<?= $sec == 'questions' ? ' active' : null; ?>" href="<?= BASE_URL ?>questions">Stories</a>

	<?php if($session->is_logged_in()): ?>
		<a class="item<?= $sec == 'profile' ? ' active' : null; ?>" href="<?= BASE_URL."user/".USER_ID ?>/">Profile</a>
		<a class="item<?= $sec == 'messages' ? ' active' : null; ?>" href="<?= BASE_URL ?>messages">Inbox<?= $newMsg; ?></a>
		<a class="item" href="<?= BASE_URL ?>logout.php">Log out</a>
	<?php endif; ?>

	<?php if(!$session->is_logged_in()): ?>
		<a class="item<?= $sec == 'login' ? ' active' : null; ?>" href="<?= BASE_URL ?>login.php">Log In</a>
		<a class="item<?= $sec == 'signup' ? ' active' : null; ?>" href="<?= BASE_URL ?>signup.php">Sign Up</a>
	<?php endif; ?>

	<?php if($session->is_logged_in() && $session->adminCheck()): ?>
		<a class="item nav_cp<?= $sec == 'staff' ? ' active' : null; ?>" href="<?= BASE_URL ?>admin">Admin Control Panel</a>
	<?php endif; ?>

	<?php if(Session::get('devmode')): ?>
		<p style="color:red;">ADMIN</p>
	<?php endif; ?>

	</div>
<?php if($session->is_logged_in()): ?>
	<div class="ui compact selection dropdown" id="menu_dropdown">
		<img class="ui avatar image" src="<?= $nvqu->img_path ?>">
		 <div class="text default"><?= $nvqu->full_name ?></div>
		<i class="dropdown icon"></i>
		<div class="menu">
			<a href="<?= USER_URL ?>" class="item">Profile</a>
			<a href="<?= BASE_URL."user/settings/" ?>" class="item">Settings</a>
			<a href="<?= BASE_URL."changelog.php" ?>" class="item">Changelog</a>
			<a href="<?= BASE_URL ?>logout.php" class="item">Log out</a>
		</div>
	</div>

<?php endif; ?>
</div>
<script>
	$('#menu_dropdown').dropdown({action:'nothing'});
</script>