<?php
require_once (__DIR__."/src/init.php");
$pageTitle = "Home Page";
$sec = "index";
$pub = (bool) !$session->is_logged_in();

include (ROOT_PATH . "inc/head.php");
?>
<body>
	<div class="ui container section main-page <?= $pub ? 'pub-view' : null; ?>">
		<div class="content front">
			<?= msgs(); ?>
			<?php if($pub) { ?>
			<?php require_once(ROOT_PATH.'inc/main.page.not.logged.php'); ?>
			<?php } else { ?>
			<div class="news-feed">
					<div class="ui segment feed-box">
					<?php require_once(ROOT_PATH.'inc/main.page.feed.form.php'); ?>
					</div>
					<hr class='rev'>
					<div class="main-feed">
					<?php require_once(ROOT_PATH.'inc/main.news.feed.php'); ?>
					</div>
			</div>
			<?php } ?>
		</div><br>
	</div>
	<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>