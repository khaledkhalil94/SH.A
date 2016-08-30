<?php if($session->is_logged_in()) exit; ?>
<div class="user user-profile public-view">
	<?= msgs(); ?>
	<div class="ui grid">
		<div class="five wide column user-info">
			<div class="image">
				<img class="ui small image" src="<?= $img_path;?>" alt="">
			</div><br>
			<div class="user-name user-username">
				<h3><?= $name; ?></h3>
				<a href="<?= BASE_URL."user/{$id}/" ?>">@<?= $username; ?></a>
			</div>
			<?php if(!empty($about)): ?>
			<div class="ui segment">
				<div class="user-info">
					<div class="user-info-about"><?= $about; ?></div>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<div class="eleven wide column">
					<div class="ui message warning">
						<p>You must <a href="/sha/login.php">login</a> or <a href="/sha/signup.php">signup</a> to view the full profile.</p>
					</div>
			<div class="ui top attached tabular menu">
				<a class="item active" data-tab="questions">Questions (3)</a>
			</div>
			<div class="ui bottom attached tab segment active" data-tab="questions">
			</div>
		</div>
	</div>
</div>