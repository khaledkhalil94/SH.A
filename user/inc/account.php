<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/src/init.php");
$session->is_logged_in() ? true : Redirect::redirectTo("/sha/signup.php");
$id = USER_ID;


$user = Student::get_user_info($id);
$session->userLock($user);


 ?>

<div class="ui segment user-settings">
	<div class="ui dividing header">
		Update your account settings
	</div>
	<div class="ui segment vertical">
		<form id="update_settings" class="ui form">
			<div class="field username">
				<label for="username">Username</label>
				<input type="text" name="username" value="<?= $user->username ?>"/>
			</div>

			<div class="field email">
				<label for="email">email</label>
				<input type="email" name="email" value="<?= $user->email ?>" />
			</div>

			<div class="field old_password">
				<label for="password">Current Password</label>
				<input type="password" name="old_password" value="" />
			</div>
			<br>
			<div class="field password">
				<label for="password">New Password</label>
				<input type="password" name="password" value="" />
			</div>

			<div class="field repassword">
				<label for="password">Re-enter your Password</label>
				<input type="password" name="repassword" value="" />
			</div>
			<input type="hidden" name="auth_token" value="<?= Token::generateToken(); ?>" />
			<br>
			<input class="ui update green button" type="submit" value="Save" />
			<a type="button" class="ui button basic" href="<?= USER_URL; ?>">Cancel</a>
		</form>
	</div>
</div>

<script src="/sha/scripts/auth/forms-vald.js">
</script>