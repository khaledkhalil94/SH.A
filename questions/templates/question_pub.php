<?php
// The view for the public
$pageTitle = "Question/Public";
include (ROOT_PATH . 'inc/head.php');
$id = (int)filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT) ?: null;

if(!$q = QNA::find_by_id($id)) {
	// if the id is not in the questions database, try to find it in the comment database.
	if ($q = Comment::find_by_id($id)) { 
		$q = $q->post_id;
		if($q == $id) $session->message("Page was not found!", "/sha/404.php", "warning");
		redirect_to_D("question.php?id={$q}#{$id}");
	} else {
		$session->message("Page was not found!", "/sha/404.php", "warning");
	}
}

// if can't find id in the student database (not a student), try to find it in the staff database (staff)
$user = Student::find_by_id($q->uid) ?: Staff::find_by_id($q->uid);

// questions
if (isset($_GET['upq'])) $session->message("You must log in to upvote.", "question.php?id={$id}", "warning");
// comments
if (isset($_POST['comment'])) $session->message("You must log in to comment.", "", "warning");
if (isset($_GET['upc'])) $session->message("You must be logged in to upvote.", "question.php?id={$id}", "warning");
$votes_count = QNA::get_votes($id);
?>
<body>
	<div class="container section">
		<a type="button" href="." class="btn btn-default">Back</a>
	<?= msgs(); ?>
			<div class="row">
				<div class="col-sm-8 blog-main">
					<div class="blog-post">
						<span><b> <?= $user->full_name()?></b> asked a question </span><br>
						<div class="time" title="<?=displayDate($q->created, "M d, Y h:m"); ?>"> <?= get_timeago($q->created, "M d, Y"); ?></div>
						<h3 class="blog-post-title"><?= $q->title; ?></h3>
						<hr>
						<p style="min-height:320px;"><?= $q->content; ?></p>
					</div>
				</div>
				<div class="col-sm-3 col-sm-offset-1 blog-sidebar" style="border-left: 1px #e2e2e2 solid;">
					<div class="sidebar-module sidebar-module-inset">
						<h4>Related questions</h4>
						<?= QNA::sidebar_news(); ?>
				</div>
			</div>
		</div>
			<a type="button" href="?upq&id=<?=$id;?>" class="btn btn-success"><?= $votes_count; ?> Upvote</a>
		<hr>
		<form action="" method="POST">
			<div class="form-group">
				<textarea class="form-control" name="content" rows="3" placeholder="Add a new comment"></textarea>
			</div>
			<br>
			<button type="comment" name="comment" class="btn btn-success">Submit</button>
		</form>

		<hr>
		<h3>Comments: </h3>
		<?php 
			$comments = Comment::get_comments($id);
			foreach ($comments as $comment):
				$votes = Comment::get_votes($comment->id); 
				$commenter = Student::find_by_id($comment->uid) ?: Staff::find_by_id($comment->uid);
				?>
				<div class="jumbotron" id="<?= $comment->id; ?>">
					<span><b> <?= $commenter->full_name()."</b> Commented <span title=\"". displayDate($comment->created, "M d, Y h:m") ."\" style=\"border-bottom: dashed 1px black;\">".get_timeago($comment->created, "M d, Y")."</span>"; ?></span>
					<span><?= $comment->id; ?></span>
					<hr>
					<p> <?= $comment->content; ?> </p>
					<hr>
					<span><?=$votes;?> Points </span>
					<a type="button" href="?upc&id=<?=$id;?>" class="btn btn-success"> Upvote</a>
				</div>
			<?php endforeach;
		?>
	</div>
</div>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>