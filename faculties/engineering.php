<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/classes/init.php");
$pageTitle = "Students";
?>

<body>
	<?php
	// Todo: Move all those codes to the Faculty class and keep this clean.
	include (ROOT_PATH . 'inc/head.php');
	$main = Faculty::display_content("main", 1)[0];
	//$img = Faculty::display_content("img", 1)[0];
	$articles = Faculty::display_content("article", 1);
	$news = Faculty::display_content("news", 1);
	?>

	<div class="main">
		<div class="container section">
			<div class="wrapper">
				<h2>Faculty of Engineering</h2>
				<div class="page-header" style="text-align:center;">
					<p><?php //$img->content; ?></p>
					<p><?= $main->content; ?></p>
				</div>
					<h3>Latest Articles</h3>
					<?php
					$i = 0;
					foreach ($articles as $article):
						if ($i == 0) { //Featuring first item
						$content = ctrim($article->content, 350);
						$content = $content . "<a href=\"../pages/articles.php?id={$article->id}\"><br>Read More</a>";
					?>
						<div class="jumbotron">
							<a href="../pages/articles.php?id=<?php echo $article->id; ?>"><h3> <?= $article->title; ?> </h3></a>
							<span class="time"> <?= displayDate($article->created, "M d, Y"); ?> </span>
							<p> <?= $content; ?> </p>
							<a href="../pages"></a>
						</div>
					<?php
						} else {
						$content = ctrim($article->content, 70);
						$content = $content . "<a href=\"../pages/articles.php?id={$article->id}\"><br>Read More</a>";
					?>
						<div class="well well-sm">
							<a href="../pages/articles.php?id=<?php echo $article->id; ?>"><h3> <?= $article->title; ?> </h3></a>
							<span class="time"> <?= displayDate($article->created, "M d, Y"); ?> </span>
							<p> <?= $content; ?> </p>
							<a href="../pages"></a>
						</div>
					<?php	
						}
					$i++;
					endforeach; ?>

					<h3>Latest news</h3>
					<?php foreach ($news as $new) { ?>
						<div class="jumbotron">
							<h6> <?= $new->content; ?> </h6>
						</div>
					<?php } ?>
				</div>
				

				<ul class="students">
					<h3>Staff</h3>
					<?php Staff::display_prof(1); ?>
				</ul>
				<div class="pagination">
				</div>
				<ul class="students">
					<h3>Students registered in engineering</h3>
					<?php Student::display_students(1); ?>
				</ul>
				<div class="pagination">
				</div>
			</div>
		</div>
	</div>

	<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>