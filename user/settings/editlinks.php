<?php
$session->userLock($user);
 ?>

<div class="ui segment user-settings-links">
	<div class="ui dividing header">
		Update your links
	</div>
	<div class="ui segment vertical">
		<form class="ui form links">
			
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
			<div class="ui update green button" type="submit">Save</div>
		</form>
	</div>
</div>

<script>

$('.ui.form')
.form({
	fields: {
		website: {
			identifier: 'website',
			optional: true,
			rules: [
			{
				type   : 'url',
				prompt : 'Invalid link.'
			}
			]
		},
		skype: {
			identifier: 'skype',
			optional: true,
			rules: [
			{
				type   : 'url',
				prompt : 'Invalid link.'
			}
			]
		},
		twitter: {
			identifier: 'twitter',
			optional   : true,
			rules: [
			{
				type   : 'url',
				prompt : 'Invalid link.'
			}
			]
		},
		github: {
			identifier: 'github',
			optional: true,
			rules: [
			{
				type   : 'url',
				prompt : 'Invalid link.'
			}
			]
		},
		facebook: {
			identifier: 'facebook',
			optional: true,
			rules: [
			{
				type   : 'url',
				prompt : 'Invalid link.'
			}
			]
		}
	},
	inline : true,
	duration: '15',
	keyboardShortcuts : false,
	on     : 'change'

});

</script>