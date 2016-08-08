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
			<input class="ui update green button" type="submit" value="Save" />
		</form>
	</div>
</div>
<script>

$('.ui.form')
.form({
	fields: {
		firstName: {
			identifier: 'firstName',
			rules: [
			{
				type   : 'empty',
				prompt : 'Please enter your name'
			},
			{
				type   : 'minLength[3]',
				prompt : 'First name must be between 3 and 10 characters'
			},
			{
				type   : 'maxLength[10]',
				prompt : 'First name must be between 3 and 10 characters'
			},
			{
				type   : 'regExp[/^[a-zA-Z0-9^\._-]{3,10}$/]',
				prompt : 'Name is not valid'
			}
			]
		},
		lastName: {
			identifier: 'lastName',
			optional: true,
			rules: [
			{
				type   : 'minLength[3]',
				prompt : 'Last name must be between 3 and 10 characters'
			},
			{
				type   : 'maxLength[10]',
				prompt : 'Last name must be between 3 and 10 characters'
			},
			{
				type   : 'regExp[/^[a-zA-Z0-9^\._-]{3,10}$/]',
				prompt : 'Last name is not valid'
			}
			]
		},
		phoneNumber: {
			identifier: 'phoneNumber',
			optional   : true,
			rules: [
			{
				type   : 'number',
				prompt : 'Phone Number must be a number'
			},
			{
				type   : 'minLength[8]',
				prompt : 'Phone Number is not valid'
			},
			{
				type   : 'maxLength[16]',
				prompt : 'Phone Number is not valid'
			}
			]
		},
		gender: {
			identifier: 'gender',
			rules: [
			{
				type   : 'empty',
				prompt : 'Please select a gender'
			}
			]
		},
		about: {
			identifier: 'about',
			rules: [
			{
				type   : 'maxLength[60]',
				prompt : 'That\'s too long!'
			}
			]
		},
		address: {
			identifier: 'address',
			optional:true
		},

	},
	inline : true,
	duration: '15',
	keyboardShortcuts : false,
	on     : 'blur'

});

</script>