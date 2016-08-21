<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/src/init.php");
$pageTitle = "Admin Control Panel";
$session->adminLock();
$display = isset($_GET['display']) ? $_GET['display'] : "date";
$sortby = isset($_GET['sortby']) ? $_GET['sortby'] : null;
$allCount = Faculty::articles_count();
switch ($display) {
	case 'pub':
		$sortby = "created DESC";
		$articles = Admin::get_articles(1, $sortby);
		$Count = count($articles);
		break;
	case 'unpub':
		$sortby = "status desc";
		$articles = Admin::get_articles(2, $sortby);
		$Count = count($articles);
		break;
	case 'del':
		$articles = Admin::get_del_articles(3, $sortby);
		$Count = count($articles);
		$allCount = Admin::get_del_count();
		break;
	
	default:
		$articles = Admin::get_articles("", $sortby); 
		$Count = count($articles);
		break;
}
$query = append_queries($_SERVER['QUERY_STRING']);
?>
<body>
	<?php
	include (ROOT_PATH . 'inc/head.php');
	?>

	<div class="main">
		<div class="container section">
			<div class="wrapper">
			<?= msgs(); ?>
			<p>TODO: search</p>
			<h2 >Articles</h2>
			</div>
			<div class="sortby">
			<p>Display:</p>
				<nav>
					<ul class="pager">
						<li><a href="articles.php">All</a></li>
						<li><a href="<?= $query['display'] ?: "?"; ?>display=pub"><?= $greenIcon; ?> Published</a></li>
						<li><a href="<?= $query['display'] ?: "?"; ?>display=unpub"><?= $greyIcon; ?> unPublished</a></li>
						<li><a href="<?= $query['display'] ?: "?"; ?>display=del"><?= $redIcon; ?> Marked for deletion</a></li>
					</ul>
				</nav>
			</div>
			<div class="sortby">
			<p>Sort by:</p>
				<nav>
					<ul class="pager">
						<li><a href="<?= $query['sortby'] ?: "?"; ?>sortby=date">Date</a></li>
						<li><a href="<?= $query['sortby'] ?: "?"; ?>sortby=status">Status</a></li>
						<li><a href="<?= $query['sortby'] ?: "?"; ?>sortby=author">Author</a></li>
					</ul>
				</nav>
			</div>
			<hr>
			
						<table class="table table-hover">
				<thead>
					<tr>
						<th class="col-md-8">Articles (<?= $Count; ?>)</th>
						<th class="col-md-2">Status</th>
						<th class="col-md-1">Edit</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($articles as $article):
						$id = $article->id;$title = $article->title;$content = $article->content;$author = $article->author;
						$created = $article->created;$status = $article->status;
					?>
					<tr>
						<td >
							<p><a href="/sha/pages/articles.php?id=<?= $id; ?>"><b><?= $title; ?></b></a></p>
							<div class="time"><?= displayDate($created); ?></div>
							<p><?= $content; ?></p>
						</td>
						<td>
							<p"><?php if($status == "1"){echo $greenIcon." Published";}elseif($status == "2"){echo $greyIcon." Unpublished";}else{echo $redIcon." Marked for deletion";}?></p>
						</td>

						<td><a type="button" href="edit.php?id=<?= $id; ?>" class="btn btn-warning">Edit</a></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>