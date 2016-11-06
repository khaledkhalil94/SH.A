<?php
require_once ("src/init.php");
$pageTitle = "Home Page";
$sec = "index";
include (ROOT_PATH . "inc/head.php");

$post = new Post();

if($session->is_logged_in()){
	$feed = $post->get_stream();

	usort($feed, 'date_compare');
	$feed = array_reverse($feed);

	$feed = array_slice($feed, 0, 30); // limit the feed items to 30
}
$pub = $session->is_logged_in() ? true : false;
$c = !$pub ? 'pub-view' : null;
?>
<body>
	<div class="ui container section main-page <?= $c ?>">
		<div class="content front">
			<?= msgs(); ?>
			<?php if(!$session->is_logged_in()) { ?>
			<?php require_once(ROOT_PATH.'inc/main.page.not.logged.php'); ?>
			<?php } else { ?>
			<div class="news-feed">
					<div class="ui segment feed-box">
					<?php require_once(ROOT_PATH.'inc/main.page.feed.form.php'); ?>
					</div>
					<hr class='rev'>
					<div class="main-feed">
					<?php
					if(empty($feed)) echo "<p>There doesn't seem to be anything here, follow some users to see what they are up to!</p>";
						else require_once(ROOT_PATH.'inc/main.news.feed.php');
					?>
					</div>
			</div>
			<?php } ?>
		</div><br>
	</div>
	<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>