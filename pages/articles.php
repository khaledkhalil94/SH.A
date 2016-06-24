<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/classes/init.php");
$pageTitle = "Students";
include (ROOT_PATH . 'inc/head.php');
$id = isset($_GET['id']) ? $_GET['id'] : null;
$article = Faculty::find_by_id($id);
$articles = Faculty::display_content("article", $article->faculty_id);
if (!isset($id)) exit("404 NOT FOUND!");
?>
<body>
	<div class="container section">
		<span><?= User::get_faculty($article->faculty_id); ?></span>
			<div class="row">
				<div class="col-sm-8 blog-main">
					<div class="blog-post">
						<h2 class="blog-post-title"><?= $article->title; ?></h2>
						<p class="blog-post-meta time"><?= displayDate($article->created, "M d, Y"); ?> by <?= $article->author; ?></p>
						<hr>
						<p><?= $article->content; ?></p>
					</div>
				</div>
				<div class="col-sm-3 col-sm-offset-1 blog-sidebar" style="border-left: 1px #e2e2e2 solid;">
					<div class="sidebar-module sidebar-module-inset">
						<h4>Other Articles</h4>
						<?php foreach ($articles as $article): 
						if ($article->id != $id): ?>
							<a href="articles.php?id=<?= $article->id; ?>"><p><?= $article->title; ?></p></a>
					<?php endif; 
					endforeach; ?>
				</div>
			</div>
		</div>
		<a type="button" href="." class="btn btn-default">Go back</a>
	</div>
</div>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>
