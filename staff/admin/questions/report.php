<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/classes/init.php");
include (ROOT_PATH . 'inc/head.php');
$session->adminLock();
$id = sanitize_id($_GET['id']) ?: null;
$q = QNA::find_by_id($id)  ?: $session->message("Page was not found!", "/sha/404.php", "danger");
if(isset($_POST['submit'])){
	if (!empty(trim($_POST['title'])) && !empty(trim($_POST['content']))) {
		if ($QNA->update($_POST)){
			$session->message("Question has been updated successfully!", ".", "success");
		} else {
			$session->message("Something went wrong!", ".", "danger");
		}
	} else {
		$session->message("Title and content can't be empty!", "", "danger");
	}
}
$user = Student::find_by_id($q->uid) ?: Staff::find_by_id($q->uid);
$reports = QNA::get_reports("questions", $id);
$post_date = displayDate($q->created, "M d, Y h:m");
$post_dateAgo = get_timeago($q->created, "M d, Y");

if (isset($_GET['dl'])) {
	if(QNA::delete_report($_GET['dl'])) $session->message("Report has been deleted.", "?id={$id}", "success");
}

?>
<body>
	<div class="container section">
		<div class="row">
			<div class="col-sm-8 blog-main">
				<div class="blog-post">
					<br>
					<h3><b><a href="/sha/questions/question.php?id=<?= $q->id; ?>"><?= $q->title; ?></a></b></h3>
					<span><b><?= $user->full_name();?></b></span>
					<div class="time" title="<?= $post_date; ?>"><?= $post_dateAgo; ?></div>
					<hr>
					<p><?= $q->content; ?></p>
					<a type="button" href="edit.php?id=<?= $id; ?>" class="btn btn-warning">Edit</a>
					<a type="button" href="?dlq=true&id=<?=$id;?>" class="btn btn-danger">Delete</a>
				</div>
			</div>
		</div><hr>
	<?= msgs(); ?>
	<h3>Reports: (<?=count($reports);?>)</h3>
		<div class="main">
		<?php foreach ($reports as $report):
			$post_date = displayDate($report->date, "M d, Y h:m");
			$post_dateAgo = get_timeago($report->date, "M d, Y");
			$reporter = $report->reporterName; ?>
			<div class="jumbotron">
				<div class="blog-post">
					<div title="<?= $post_date; ?>"><a href="/sha/user/<?=$report->reporter;?>/"><?=$reporter;?></a> reported this post <?= $post_dateAgo; ?></div>
					<h3 class="blog-post-title"><?= $report->content; ?></h3>
					<hr>
				</div>
				<a type="button" href="?id=<?=$q->id;?>&dl=<?=$report->id;?>" class="btn btn-danger">Delete</a>
			</div>

		<?php endforeach; BackBtn(); ?>

		</div>
	</div>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>
