<?php
$session->userLock($user);
 ?>

<div class="ui segment user-settings-links">
	<div class="ui dividing header">
		Update your links
	</div>
	<div class="ui segment vertical">
		<form id="user-links-form" class="ui form links">
			
			<div class="field website">
				<label>Website</label>
				<input type="text" name="website" value="<?= $user->website ?>" />
			</div>

			<div class="field skype">
				<label>Skype</label>
				<input type="text" name="skype" value="<?= $user->skype ?>" />
			</div>

			<div class="field twitter">
				<label>Twitter</label>
				<input type="text" name="twitter" value="<?= $user->twitter ?>" />
			</div>
			<div class="field github">
				<label>Github</label>
				<input type="text" name="github" value="<?= $user->github ?>" />
			</div>
			<div class="field facebook">
				<label>Facebook</label>
				<input type="text" name="facebook" value="<?= $user->facebook ?>" />
			</div><br><br>
			<input type="hidden" name="auth_token" value="<?= Token::generateToken(); ?>" />
			<input class="ui update green button" type="submit" value="Save" />
		</form>
	</div>
</div>

<script src="/sha/scripts/auth/forms-vald.js">
</script>