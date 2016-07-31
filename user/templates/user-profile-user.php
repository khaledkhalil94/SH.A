<div class="user user-profile">
	<?= msgs(); ?>
		<div class="ui grid">
			<div class="five wide column side-bar user-info">
				<div class="image">
					<img class="ui small image" src="<?= $img_path;?>" alt="">
				</div><br>
				<div class="user-name user-username">
					<h3><?= $name; ?></h3>
					<a href="<?= BASE_URL."user/{$id}/" ?>">@<?= $username; ?></a>
				</div>
				<div class="ui segment">
					<div class="user-info">
						<div class="user-info-about">BIO/ABOUT</div>
					</div>
				</div>

				<div class="ui segment user-extrainfo">
					<div class="user-links-header">
						<div class="ui header"><h4>Personal info</h4></div>
						<button id="extrainfo-collapse" class="ui icon button extrainfo-collapse"><i id="btn-extrainfo-angle-up" class="angle up icon"></i></button>
					</div>
					<div id="user-extrainfo-list" class="ui list">
						<?php if(!empty($location) && $locationP): ?>
						<div class="item" title="Location">
							<i class="location arrow icon"></i>
							<div class="content user-info-location"><?= $location ?></div>
						</div>
						<?php endif; ?>
						<?php if(!empty($email) && $emailP): ?>
						<div class="item" title="E-mail">
							<i class="mail outline icon"></i>
							<div class="content user-info-email"><?= $email; ?></div>
						</div>
						<?php endif; ?>
						<?php if(!empty($gender) && $genderP): ?>
						<div class="item" title="Gender">
							<?php if($gender == 'male'): ?>
							<i class="male icon"></i>
							<?php endif; ?>
							<?php if($gender == 'female'): ?>
							<i class="female icon"></i>
							<?php endif; ?>
							<div class="content user-info-gender"><?= $gender; ?></div>
						</div>
						<?php endif; ?>
						<?php if(!empty($phoneNumber) && $phoneNumberP): ?>
						<div class="item" title="Phone Number">
							<i class="mobile icon"></i>
							<div class="content user-info-phone-number"><?= $phoneNumber; ?></div>
						</div>
						<?php endif; ?>
						<?php if(!empty($birhDate) && $birthDateP): ?>
						<div class="item" title="Birth Date">
							<i class="birthday icon"></i>
							<div class="content user-info-birth-date">birth-date</div>
						</div>
						<?php endif; ?>
						<?php if(!empty($register_date)): ?>
						<div class="item" title="Registeration Date">
							<i class="checked calendar icon"></i>
							<div class="content user-info-joined-date" id="user-joined-date"><?= $register_date; ?></div>
						</div>
						<?php endif; ?>
					</div>
				</div>

			<?php if(!$emptyLinks){ ?>
				<div class="ui segment user-links">
					<div class="user-links-header">
						<div class="ui header"><h4>Links</h4></div>
						<button id="links-collapse" class="ui icon button links-collapse"><i id="btn-angle-up" class="angle up icon"></i></button>
						<?php if($linksP){ ?>
						<a class="ui icon button" href="/sha/user/settings/?st=up" id="links-edit" data-variation="mini" data-tooltip="Your links are public" data-inverted="">
						  <i class="lock icon"></i>
						</a>
						<?php } else { ?>
						<a class="ui icon button" href="/sha/user/settings/?st=up" id="links-edit" data-variation="mini" data-tooltip="Your links are private" data-inverted="">
						  <i class="lock icon"></i>
						</a>
						<?php } ?>
					</div>

					<div id="user-links-list" class="ui list">
						<?php if(!empty($website)): ?>
						<div class="item" title="<?= $user->firstName; ?>'s Website">
							<i class="linkify icon"></i>
							<a href="<?= $website ?>" class="content user-info-global"><?= $website ?></a>
						</div>
						<?php endif; ?>
						<?php if(!empty($skype)): ?>
						<div class="item" title="<?= $user->firstName; ?>'s Skype">
							<i class="skype icon"></i>
							<a href="<?= $skype ?>" class="content user-info-skype"><?= $skype ?></a>
						</div>
						<?php endif; ?>
						<?php if(!empty($twitter)): ?>
						<div class="item" title="<?= $user->firstName; ?>'s Twitter">
							<i class="twitter icon"></i>
							<a href="<?= $twitter ?>" class="content user-info-twitter"><?= $twitter ?></a>
						</div>
						<?php endif; ?>
						<?php if(!empty($github)): ?>
						<div class="item" title="<?= $user->firstName; ?>'s Github">
							<i class="github icon"></i>
							<a href="<?= $github ?>" class="content user-info-github"><?= $github ?></a>
						</div>
						<?php endif; ?>
						<?php if(!empty($facebook)): ?>
						<div class="item" title="<?= $user->firstName; ?>'s Facebook">
							<i class="facebook icon"></i>
							<a href="<?= $facebook ?>" class="content user-info-facebook"><?= $facebook ?></a>
						</div>
						<?php endif; ?>
					</div>
				</div>
			<?php } ?>
			</div>
			<div class="eleven wide column">
				<div class="profile-info ui vertical padded segment">
					<div class="user-interactivity">
						<button class="ui button green follow-btn">Follow</button>
						<a href="/sha/messages/compose.php?to=<?= $id ?>" class="ui button basic msg-btn">Message</a>
						<a href="/sha/staff/admin/user/student.php?id=<?= $id ?>" class="ui button basic red">Preview user</a>
					</div>
					<div class="user-setting">
						<i class="setting big icon"></i>
					</div>

				</div>

				<div class="ui top attached tabular menu">
					<a class="item active" data-tab="feed">Feed</a>
					<a class="item" data-tab="pics">Pictures (5)</a>
					<a class="item" data-tab="following">1206 . Following</a>
					<a class="item" data-tab="followers">2164 . Followers</a>
					<a class="item" data-tab="questions">Questions (3)</a>
				</div>
				<div class="ui bottom attached tab segment active" data-tab="feed">
				</div>
				<div class="ui bottom attached tab segment" data-tab="pics">
				</div>
				<div class="ui bottom attached tab segment" data-tab="following">
				</div>
				<div class="ui bottom attached tab segment" data-tab="followers">
				</div>
				<div class="ui bottom attached tab segment" data-tab="questions">
				</div>

				<?php if ($self): ?>
				<a class="btn hide btn-default" href="<?= BASE_URL."user/settings/editstudent.php"?>" role="button">Update your information</a>
				<a class="btn hide btn-default" href="<?= BASE_URL."user/settings/editprivacy.php"?>" role="button">Update your privacy</a>
				<a class="btn hide btn-default" href="<?= BASE_URL."user/settings/account.php"?>" role="button">Change account settings</a>
				<?php endif; ?>
			</div>
		</div>
</div>