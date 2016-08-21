<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/src/init.php");
$pageTitle = "Students";
$sec = "pages";
include (ROOT_PATH . 'inc/head.php');
if(isset($_GET['display'])){
	$dsp = $_GET['display'];
	$articles = Faculty::get_content("article", $dsp);
} else {
	$articles = Faculty::get_content("article");
	
}
$mainContent = Faculty::main_content();
?>
<body>
	<div class="container section">
	<?= msgs(); ?>
		<div class="page-header" style="text-align:center;">
			<p><?= $mainContent->content; ?></p>
			<?php if ($session->adminCheck()): ?>
				<a type="button" href="edit.php?id=<?= $mainContent->id; ?>" class="btn btn-warning">Edit</a>
			<?php endif; ?>
		</div>
		<h3>Latest Articles</h3>
		<nav class="navbar navbar-inverse" style="color:white; width:726px;">
		  <div class="container-fluid">
		    <div class="navbar-header">
		      <span class="navbar-brand">Show articles from: </span>
		    </div>
		    <ul class="nav navbar-nav">
		      <li <?= !isset($dsp) ? "class=\"active\"" : null; ?>><a href=".">All</a></li>
		      <li <?= isset($dsp) && $dsp == 1 ? "class=\"active\"" : null; ?>><a href="?display=1">Engineering</a></li>
		      <li <?= isset($dsp) && $dsp == 2 ? "class=\"active\"" : null; ?>><a href="?display=2">Computer Science</a></li> 
		      <li <?= isset($dsp) && $dsp == 3 ? "class=\"active\"" : null; ?>><a href="?display=3">Medicine</a></li> 
		    </ul>
		  </div>
		</nav>
		<!-- TODO: Add pagination -->
		<?php if ($session->adminCheck()): ?>
			<a type="button" href="create.php" class="btn btn-default">Create a new article</a>
		<?php endif; ?>
		<div class="row">
			<div class="col-sm-9 blog-main">
				<?php 
				$i = 0;
				foreach ($articles as $article):
					$subject = ctrim($article->content, 150, $article->id);
					if ($i == 0) {  //Featuring first item ?>
						<div class="jumbotron">
							<a href="../pages/articles.php?id=<?php echo $article->id; ?>"><h3> <?= $article->title; ?> </h3></a>
							<span class="time"> <?= ucwords(str_replace("_", " ", $article->fac_name)); ?> </span><br>
							<span class="time"> <?= displayDate($article->created, "M d, Y"); ?></span>
							<p> <?= $subject; ?> </p>
							<a href="../pages"></a>
						</div>
						<p>More stories</p>
					<?php
					} else { ?>
						<div class="well">
							<a href="../pages/articles.php?id=<?php echo $article->id; ?>"><h3> <?= $article->title; ?> </h3></a>
							<span class="time"> <?= ucwords(str_replace("_", " ", $article->fac_name)); ?> </span><br>
							<span class="time"> <?= displayDate($article->created, "M d, Y"); ?></span>
							<p> <?= $subject; ?> </p>
							<a href="../pages"></a>
						</div>
					<?php }
					$i++;
			 	endforeach; ?>
		 	</div>
			<div class="col-sm-2 col-sm-offset-1 blog-sidebar" style="border-left: 1px #e2e2e2 solid;">
				<div class="sidebar-module sidebar-module-inset">
					<h4>News</h4>
					<?= Faculty::sidebar_news(); ?>
				</div>
			</div>
	 	</div>
	</div>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>
