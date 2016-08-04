<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");
$session->is_logged_in() ? true : redirect_to_D("/sha/signup.php");
$id = $session->user_id;


$studentInfo = StudentInfo::find_by_id($id);
$session->userLock($studentInfo);

if (isset($_POST['submit'])) {

	if($studentInfo->update()){
		$session->message("Your information have been updated", USER_URL);
	} else {
		$session->message($_SESSION['fail']['sqlerr'], USER_URL);
	}
}

 ?>

<div class="ui segment">
	<div class="ui dividing header">
		Update your account settings
	</div>
	<div class="ui segment vertical">
		<form class="ui form" action="<?php echo "account.php" ?>" method="POST">
			<div class="field">
				<label for="username">Username</label>
				<input type="text" name="username" value="<?php echo $studentInfo->username ?>"/>
			</div>

			<div class="field">
				<label for="password">Current Password</label>
				<input type="text" name="cupassword" value="<?php echo $studentInfo->password ?>" />
			</div>

			<div class="field">
				<label for="password">Password</label>
				<input type="text" name="password" value="<?php echo $studentInfo->password ?>" />
			</div>

			<div class="field">
				<label for="password">Re-enter your Password</label>
				<input type="text" name="repassword" value="<?php echo $studentInfo->password ?>" />
			</div>

			<div class="field">
				<label for="email">email</label>
				<input type="email" name="email" value="<?php echo $studentInfo->email ?>" />
				<input type="hidden" name="id" value="<?php echo $studentInfo->id ?>" />
			</div>


			<input type="submit" class="ui button primary" name="submit" value="Update" />
			<a type="button" class="ui button basic" href="<?= USER_URL; ?>">Cancel</a>
		</form>
	</div>
</div>

<script>
$('.ui.form').form({
	fields: {
		username: {
			identifier: 'username',
			rules: [
			{
				type   : 'empty',
				prompt : 'Username can\'t be empty.'
			},
			{
				type   : 'minLength[4]',
				prompt : 'Username must be between 3 and 10 characters.'
			},
			{
				type   : 'maxLength[15]',
				prompt : 'Username must be between 3 and 10 characters.'
			},
			{
				type   : 'regExp[/^[a-zA-Z0-9_]{4,15}$/]',
				prompt : 'Username may only contain alphanumeric characters or \'_\''
			}
			]
		},
		repassword: {
			identifier: 'repassword',
			rules: [
			{
				type   : 'match[password]',
				prompt : 'Passwords don\'t match.'
			}
			]
		},
		email: {
			identifier: 'email',
			rules: [
			{
				type   : 'email',
				prompt : 'E-mail is not valid.'
			}]
		}
	},
		inline : true,
		duration: '15',
		on     : 'change'
	});
</script>