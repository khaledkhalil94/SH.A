<div class="user user-profile self" user-id="<?= $id ?>">
	<?= msgs(); ?>
	<div class="ui grid">
		<div class="five wide column side-bar user-info">
			<div class="ui special cards">
				<div class="card">
					<div class="image">
						<div class="ui dimmer">
							<div class="content">
								<div id="pp_actions" class="top right profile-picture-actions">
									<?php if($has_pic){ ?>			
										<div id="viewPicture" class="ui icon button" data-variation="mini" data-content="View Picture" >
										  <i data-variation="mini" class="unhide icon link"></i>
										</div>
										<div id="changePicture" class="ui small icon button" data-content="Change Picture" data-variation="mini">
										  <i class="edit icon link"></i>
										</div>
										<div id="deletePicture" class="ui small icon button" data-content="Delete Picture" data-variation="mini">
										  <i class="trash outline icon link"></i>
										</div>
									<?php } else { ?>
										<div id="uploadPicture" class="ui small icon button" data-content="Upload Picture" data-variation="mini">
										  <i class="cloud upload icon link"></i>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
						<img class="ui medium rounded image" id="proflePicture" src="<?= $img_path;?>">
					</div>
				</div>
				<input style="display:none;" type="file" id="myFile">
			</div>

			<br>
			<div class="user-name user-username">
				<h3><?= $name; ?></h3>
				<a href="<?= BASE_URL."user/{$id}/" ?>">@<?= $username; ?></a>
			</div>
			<div class='user-points'>
				<a class='ui label' style='color:#04c704;' title='Total Points'>
					<i class='thumbs outline up icon'></i>
					<?= User::get_user_points($id); ?>
				</a>
			</div>
			<div class="ui segment">
				<?php if(!empty($about)): ?>
				<a class="ui icon button" href="/sha/user/settings/?st=ui&about_autofocus" id="about-edit" data-variation="mini" data-tooltip="Edit" data-inverted="">
				  <i data-variation="mini" class="edit icon"></i>
				</a>
				<?php endif; ?>
				<div class="user-info">
					<div class="user-info-about"><?= empty($about) ? "<a href='/sha/user/settings/?st=ui&about_autofocus'>Write some words about yourself!</a>" : $about; ?></div>
				</div>
			</div>

			<div class="ui segment user-extrainfo">
				<div class="user-links-header">
					<div class="ui header"><h4>Personal info</h4></div>
					<button data-variation="mini" title="Minimize" id="extrainfo-collapse" class="ui icon button extrainfo-collapse">
						<i id="btn-extrainfo-angle-up" class="angle up icon"></i>
					</button>
				<?php if($pub){ ?>
					<a class="ui icon button" href="/sha/user/settings/?st=up" id="extrainfo-edit" data-variation="mini" data-tooltip="Your information are public" data-inverted="">
					  <i class="world icon"></i>
					</a>
				<?php } elseif($custom) { ?>
					<a class="ui icon button" href="/sha/user/settings/?st=up" id="extrainfo-edit" data-variation="mini" data-tooltip="Your information are custom" data-inverted="">
					  <i class="setting icon"></i>
					</a>
				<?php } else { ?>			
					<a class="ui icon button" href="/sha/user/settings/?st=up" id="extrainfo-edit" data-variation="mini" data-tooltip="Your information are private" data-inverted="">
					  <i class="lock icon"></i>
					</a>

					<?php } ?>
				</div>
				<div id="user-extrainfo-list" class="ui list">
					<?php if(!empty($location)): ?>
					<div class="item" title="Location">
						<i class="location arrow icon"></i>
						<div class="content user-info-location"><?= $location ?>
						</div>
					</div>
					<?php endif; ?>
					<?php if(!empty($email)): ?>
					<div class="item" title="E-mail">
						<i class="mail outline icon"></i>
						<div class="content user-info-email"><?= $email; ?></div>
					</div>
					<?php endif; ?>
					<?php if(!empty($gender)): ?>
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
					<?php if(!empty($phoneNumber)): ?>
					<div class="item" title="Phone Number">
						<i class="mobile icon"></i>
						<div class="content user-info-phone-number"><?= $phoneNumber; ?></div>
					</div>
					<?php endif; ?>
					<div class="item" title="Birth Date">
						<i class="birthday icon"></i>
						<div class="content user-info-birth-date">birth-date</div>
					</div>
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
					<button title="Minimize" data-variation="mini" id="links-collapse" class="ui icon button links-collapse">
						<i id="btn-angle-up" class="angle up icon"></i>
					</button>
					<?php if($linksP){ ?>
					<a class="ui icon button" href="/sha/user/settings/?st=up" id="links-edit" data-variation="mini" data-tooltip="Your links are public" data-inverted="">
					  <i class="world icon"></i>
					</a>
					<?php } else { ?>
					<a class="ui icon button" href="/sha/user/settings/?st=up" id="links-edit" data-variation="mini" data-tooltip="Your links are private" data-inverted="">
					  <i class="lock icon"></i>
					</a>
					<?php } ?>
				</div>

				<div id="user-links-list" class="ui list">
					<?php if(!empty($website)): ?>
					<div class="item" title="your Website">
						<i class="linkify icon"></i>
						<a href="<?= $website ?>" class="content user-info-global"><?= $website ?></a>
					</div>
					<?php endif; ?>
					<?php if(!empty($skype)): ?>
					<div class="item" title="your Skype">
						<i class="skype icon"></i>
						<a href="<?= $skype ?>" class="content user-info-skype"><?= $skype ?></a>
					</div>
					<?php endif; ?>
					<?php if(!empty($twitter)): ?>
					<div class="item" title="your Twitter">
						<i class="twitter icon"></i>
						<a href="<?= $twitter ?>" class="content user-info-twitter"><?= $twitter ?></a>
					</div>
					<?php endif; ?>
					<?php if(!empty($github)): ?>
					<div class="item" title="your Github">
						<i class="github icon"></i>
						<a href="<?= $github ?>" class="content user-info-github"><?= $github ?></a>
					</div>
					<?php endif; ?>
					<?php if(!empty($facebook)): ?>
					<div class="item" title="your Facebook">
						<i class="facebook icon"></i>
						<a href="<?= $facebook ?>" class="content user-info-facebook"><?= $facebook ?></a>
					</div>
					<?php endif; ?>
				</div>
			</div>
			<?php } ?>
		</div>
		<div class="eleven wide column profile-body">
			<div class="profile-info ui vertical padded segment">
				<div class="user-setting">
					<a class="" href="<?= BASE_URL."user/settings/"?>">
						<div title="Edit your settings" data-variation="mini" class="ui icon link button">
							<i class="settings big icon"></i>
						</div>
					</a>
				</div>
			</div>
			<div class="ui top attached tabular menu">
				<a class="item active" data-tab="activity">Activity</a>
				<a class="item" data-tab="following">Following (<?= $following_count ?>)</a>
				<a class="item" data-tab="followers">Followers (<?= $followers_count ?>)</a>
				<a class="item" data-tab="questions">My Questions (<?= $q_count ?>)</a>
				<a class="item" data-tab="saved">Saved (<?= $saved_count ?>)</a>
			</div>
			<div class="ui bottom attached tab segment active" data-tab="activity">
			</div>
			<div class="ui bottom attached tab segment" data-tab="following">
			</div>
			<div class="ui bottom attached tab segment" data-tab="followers">
			</div>
			<div class="ui bottom attached tab segment" data-tab="questions">
			</div>
			<div class="ui bottom attached tab segment" data-tab="saved">
			</div>
		</div>
	</div>
</div>



<div class="ui page dimmer">
	<div class="content">
		<div class="center">
			<div id="dimmer-close" class="close-round"><i class="remove icon"></i></div>
			<div class="ui grid centered image-details">
				<div class="seven wide column image">
					<img class="ui medium rounded image" id="pic_details_pp" src="">
				</div>
				<div class="seven wide column info">
					<div class="ui inverted relaxed large divided list">
						<div id="pic_details_name" class="item"></div>
						<div id="pic_details_size" class="item"></div>
						<div id="pic_details_dim" class="item"></div><br>
						<a id="pic_details_link" title="Download Photo" href="" download="proposed_file_name"><i class="download circular inverted link icon"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
