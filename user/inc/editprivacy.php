<div class="ui segment user-settings-privacy">
	<div class="ui dividing header">
		Update your privacy options
	</div>
	<div class="ui segment vertical">
		<form class="ui form" action="" method="POST">

			<div class="field">
				<label>E-mail</label>
				<select name="email_privacy">
					<option value="1" <?= $user->email_privacy == "1" ? "selected" : null; ?>>Public</option>
					<option value="0" <?= $user->email_privacy == "0" ? "selected" : null; ?>>Private</option>
				</select>
			</div>
			<div class="field">
				<label>Location</label>
				<select name="location_privacy">
					<option value="1" <?= $user->location_privacy == "1" ? "selected" : null; ?>>Public</option>
					<option value="0" <?= $user->location_privacy == "0" ? "selected" : null; ?>>Private</option>
				</select>
			</div>
			<div class="field">
				<label>Phone Number</label>
				<select name="phoneNumber_privacy">
					<option value="1" <?= $user->phoneNumber_privacy == "1" ? "selected" : null; ?>>Public</option>
					<option value="0" <?= $user->phoneNumber_privacy == "0" ? "selected" : null; ?>>Private</option>
				</select>
			</div>
			<div class="field">
				<label>Gender</label>
				<select name="gender_privacy">
					<option value="1" <?= $user->gender_privacy == "1" ? "selected" : null; ?>>Public</option>
					<option value="0" <?= $user->gender_privacy == "0" ? "selected" : null; ?>>Private</option>
				</select>
			</div>
			<div class="field">
				<label>Birth Date</label>
				<select name="birthDate_privacy">
					<option value="1" <?= $user->gender_privacy == "1" ? "selected" : null; ?>>Public</option>
					<option value="0" <?= $user->gender_privacy == "0" ? "selected" : null; ?>>Private</option>
				</select>
			</div>
			<div class="field">
				<label>Links</label>
				<select name="links_privacy">
					<option value="1" <?= $user->links_privacy == "1" ? "selected" : null; ?>>Public</option>
					<option value="0" <?= $user->links_privacy == "0" ? "selected" : null; ?>>Private</option>
				</select>
			</div>
		</form>
	</div>
</div>

