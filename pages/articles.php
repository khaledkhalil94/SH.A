<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/src/init.php");
$sec = "pages";
$pageTitle = "Students";
include (ROOT_PATH . 'inc/head.php');
if (!isset($_GET['id'])) exit("404 NOT FOUND!");
$id = isset($_GET['id']) ? $_GET['id'] : null;
if(!Faculty::find_by_id($id)) $session->message("Page was not found!", "/sha/404.php", "danger");
$article = Faculty::get_article($id);
$limit = 2;
?>
<body>
	<div class="container section">
	<?= msgs(); ?>
			<div class="row">
				<div class="col-md-8 blog-main">
					<div class="blog-post">
						<h2 class="blog-post-title"><?= $article->title; ?></h2>
						<p class="blog-post-meta time"><?= displayDate($article->created, "M d, Y"); ?> by <?= $article->author; ?></p>
						<hr>
						<p style="min-height:320px;"><?= $article->content; ?></p>
					</div>
				</div>
				<div class="col-md-4 blog-sidebar" style="border-left: 1px #e2e2e2 solid;">
					<div class="sidebar-module sidebar-module-inset">
					<?php if($article->type == "article") { ?>
						<h4>Other Articles in <?= ucwords(str_replace("_", " ", $article->fac_name)); ?></h4>
						<?= Faculty::sidebar_content($article->id, $article->faculty_id) ?: "There's nothing here!"; ?>
						<hr>
						<h4>News</h4>
						<?= Faculty::sidebar_news(); ?>
					<?php } elseif($article->type == "news") { ?>
						<h3>Latest Articles</h3>
						<h5><b>Engineering</b></h5>
						<?= Faculty::sidebar_content($article->id, 1, $limit); ?>
						<h5><b>cs</b></h5>
						<?= Faculty::sidebar_content($article->id, 2, $limit); ?>
						<h5><b>md</b></h5>
						<?= Faculty::sidebar_content($article->id, 3, $limit); ?>
						<hr>
						<h4>Other News</h4>
						<?= Faculty::sidebar_news($id, $limit); ?>
					<?php } ?>
				</div>
			</div>
		</div>
		<a type="button" href="." class="btn btn-default">Go back</a>
		<?php if($session->adminCheck()): ?>
			<a type="button" href="edit.php?id=<?= $id; ?>" class="btn btn-warning">Edit</a>
		<?php endif; ?>
	</div>
</div>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>
