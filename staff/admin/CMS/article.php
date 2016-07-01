<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/classes/init.php");
$pageTitle = "Students";
include (ROOT_PATH . 'inc/head.php');
$id = isset($_GET['id']) ? $_GET['id'] : null;
if(!Faculty::find_by_id($id)) $session->message("Page was not found!", "/sha/404.php", "danger");
$article = Faculty::find_by_id($id);

if (!isset($id)) exit("404 NOT FOUND!");
?>
<body>
	<div class="container section">
	<?= msgs(); ?>
		<span><?= User::get_faculty($article->faculty_id); ?></span><br>
		<span><?= array_search($article->faculty_id, $faculties); ?></span>
			<div class="row">
				<div class="col-sm-8 blog-main">
					<div class="blog-post">
						<h2 class="blog-post-title"><?= $article->title; ?></h2>
						<p class="blog-post-meta time"><?= displayDate($article->created, "M d, Y"); ?> by <?= $article->author; ?></p>
						<hr>
						<p style="min-height:420px;"><?= $article->content; ?></p>
					</div>
				</div>
				<div class="col-sm-3 col-sm-offset-1 blog-sidebar" style="border-left: 1px #e2e2e2 solid;">
					<div class="sidebar-module sidebar-module-inset">
						<h4>Other Articles</h4>
						<?= Faculty::sidebar_content($article->type, $id); ?>
						<hr>
						<h4>News</h4>
						<?= Faculty::sidebar_news(); ?>
				</div>
			</div>
		</div>
		<a type="button" href="./articles.php" class="btn btn-default">Go back</a>
		<?php if($session->adminCheck()): ?>
			<a type="button" href="edit.php?id=<?= $id; ?>" class="btn btn-warning">Edit</a>
		<?php endif; ?>
	</div>
</div>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>
