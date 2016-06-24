<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/classes/init.php");
$pageTitle = "Students";
include (ROOT_PATH . 'inc/head.php');
$main = Faculty::display_content("main", 0)[0];
if(isset($_GET['display'])){
	$dsp = $_GET['display'];
	switch ($dsp) {
		case 'eng':
			$articles = Faculty::display_content("article", 1);
			break;
		case 'cs':
			$articles = Faculty::display_content("article", 2);
			break;
		case 'md':
			$articles = Faculty::display_content("article", 3);
			break;
		default:
			$articles = Faculty::get_all_content("article");
			break;
	}
} else {
	$articles = Faculty::get_all_content("article");
}

?>
<body>
	<div class="container section">
			<div class="page-header" style="text-align:center;">
				<p><?= $main->content; ?></p>
			</div>
			<h3>Latest Articles</h3>
			<nav class="navbar navbar-inverse" style="color:white; width:726px;">
			  <div class="container-fluid">
			    <div class="navbar-header">
			      <span class="navbar-brand">Show articles from: </span>
			    </div>
			    <ul class="nav navbar-nav">
			      <li <?= !isset($dsp) ? "class=\"active\"" : null; ?>><a href=".">All</a></li>
			      <li <?= isset($dsp) && $dsp == 'eng' ? "class=\"active\"" : null; ?>><a href="?display=eng">Engineering</a></li>
			      <li <?= isset($dsp) && $dsp == 'cs' ? "class=\"active\"" : null; ?>><a href="?display=cs">Computer Science</a></li> 
			      <li <?= isset($dsp) && $dsp == 'md' ? "class=\"active\"" : null; ?>><a href="?display=md">Medicine</a></li> 
			    </ul>
			  </div>
			</nav>
			<!-- TODO: Add pagination -->
			<?php foreach ($articles as $article):
			$content = ctrim($article->content, 350);
			$content = $content . "<a href=\"../pages/articles.php?id={$article->id}\"><br>Read More</a>";
			?>
			<div class="well">
				<a href="../pages/articles.php?id=<?php echo $article->id; ?>"><h3> <?= $article->title; ?> </h3></a>
				<span class="time"> <?= User::get_faculty($article->faculty_id); ?> </span><br>
				<span class="time"> <?= displayDate($article->created, "M d, Y"); ?></span>
				<p> <?= $content; ?> </p>
				<a href="../pages"></a>
			</div>
		<?php endforeach; ?>
	</div>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>
