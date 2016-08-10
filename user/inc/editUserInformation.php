<?php

$session->userLock($user);
 ?>


<div class="ui segment user-settings-information">
	<div class="ui dividing header">
		Update your information
	</div>
	<div class="ui segment vertical">
		<form class="ui form" id="form_updInfo">
			
			<div class="field firstName">
				<label>First Name</label>
				<input type="text" name="firstName" value="<?= $user->firstName ?>" />
			</div>

			<div class="field lastName">
				<label>Last Name</label>
				<input type="text" name="lastName" value="<?= $user->lastName ?>" />
			</div>

			<div class="field address">
				<label>Address</label>
				<input type="text" name="address" value="<?= $user->address ?>" />
			</div>
			<div class="field phoneNumber">
				<label>Phone Number</label>
				<input type="text" name="phoneNumber" value="<?= $user->phoneNumber ?>" />
			</div>
			<div class="field about">
				<label>About</label>
				<textarea <?php if(isset($_GET['about_autofocus'])) echo "autofocus"; ?> type="text" rows="2" name="about" placeholder="Introduce yourself in a few words"><?= $user->about; ?></textarea>
			</div>
			<div class="field gender">
				<label>Gender</label>
				<select name="gender">
				  <option value="male" <?= $user->gender == "male" ? "selected" : null; ?>>Male</option>
				  <option value="female" <?= $user->gender == "female" ? "selected" : null; ?> >Female</option>
				</select>
			</div><br>
			<input type="hidden" name="auth_token" value="<?= Token::generateToken(); ?>" />
			<input class="ui update green button" type="submit" value="Save" />
		</form>
	</div>
</div>
<script src="/sha/scripts/auth/forms-vald.js">
</script>