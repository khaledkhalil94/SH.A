<?php

$session->userLock($user);




$img_path = Images::get_profile_pic($user);
$has_pic = (bool)$user->has_pic;
$Images->has_pic = $has_pic;
$Images->id = $id;
 ?>
			<div class="hide">
				<?php // Upload or Change profile picture
					if (isset($_POST['upload'])) {
							if (empty($_FILES['userfile']['name'])) {
								echo "Please select a valid photo";
							} else {
								$has_pic ? Student::update_pic($_POST) : Student::upload_pic($_POST);
							$session->message("Your profile picute has been changed successfully.",  USER_URL);
							}
					}
				?>
						<div class="image"><img src=<?= $img_path; ?> alt="" style="width:228px;"></div>
				 <p>Change your profile picture</p> 

				<form enctype="multipart/form-data" action="<?php echo "editUserInformation.php?id=". $id ?>" method="POST">
					<input type="hidden" name="MAX_FILE_SIZE" value=<?= MAX_PIC_SIZE ?> />
					<input name="userfile" type="file" />
					<input type="hidden" name="id" value="<?= $student->id ?>" />
					<input type="submit" name="upload" value="Upload" />
				</form>
				<br>
				
				<?php //Delete profile picture
				 if($has_pic):
				  ?>
					<form action="<?php echo "editUserInformation.php?id=". $id ?>" method="POST">
					<?php if (isset($_POST['delete'])) { 
							Student::delete_pic();
							header("Refresh:0");
						   }?>
						<input type="submit" name="delete" class="btn btn-secondary" value="Delete Picture" />
					</form>
				<?php endif; ?>
			</div>

<div class="ui segment user-settings-information">
	<div class="ui dividing header">
		Update your information
	</div>
	<div class="ui segment vertical">
		<form class="ui form">
			
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
			</div><br><br>
			<div class="ui update green button" type="submit">Save</div>
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
				type   : 'minLength[10]',
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
		}
	},
	inline : true,
	duration: '15',
	keyboardShortcuts : false,
	on     : 'blur'

});

</script>