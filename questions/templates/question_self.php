<?php
// The view for the users
$pageTitle = "Question/User";
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
$self = $session->userCheck($user);

// questions
if (isset($_GET['upq'])) QNA::upvote($q, USER_ID);
if (isset($_GET['dnq'])) QNA::downvote($id, USER_ID);
if (isset($_GET['dlq'])) QNA::delete($q);
if (isset($_GET['repoq'])) QNA::report($id);
// comments
if (isset($_POST['comment'])) Comment::comment();
if (isset($_GET['upc'])) {
	$comment = Comment::find_by_id($_GET['upc']);
	QNA::upvote($comment, USER_ID);
}
if (isset($_GET['dnc'])) if (QNA::downvote($_GET['dnc'], USER_ID)) $session->message("Downvoted.", "", "success");
if (isset($_GET['dlc'])) Comment::delete(Comment::find_by_id($_GET['dlc']));
if (isset($_GET['repoc'])) Comment::report($_GET['repoc']);
$voted = QNA::has_voted($id, USER_ID);

$votes_count = QNA::get_votes($id);
?>
<body>
	<div class="container section">
		<a type="button" href="." class="btn btn-default">Back</a>
	<?= msgs(); ?>
			<div class="row">
				<div class="col-sm-8 blog-main">
					<div class="blog-post">
						<span><b><?= !$self ? $user->full_name() : "You";?></b> asked a question </span><br>
						<div class="time" title="<?=displayDate($q->created, "M d, Y h:m"); ?>"> <?= get_timeago($q->created, "M d, Y"); ?></div>
						<h3 class="blog-post-title"><?= $q->title; ?></h3>
						<hr>
						<p style="min-height:320px;"><?= $q->content; ?></p>
					</div>
				</div>
				<div class="col-sm-3 col-sm-offset-1 blog-sidebar" style="border-left: 1px #e2e2e2 solid;">
					<div class="sidebar-module sidebar-module-inset">
						<h4>Related questions</h4>
						<?= QNA::sidebar_content($id); ?>
				</div>
			</div>
		</div>
		<?php if($voted){ ?>
			<a type="button" href="?dnq&id=<?=$id;?>" class="btn btn-danger"><?= $votes_count; ?> Downvote</a>
		<?php } else {?>
			<a type="button" href="?upq&id=<?=$id;?>" class="btn btn-success"><?= $votes_count; ?> Upvote</a>
		<?php } ?>
		<?php if ($session->is_logged_in() && !$session->userCheck($user)) { ?>
			<a type="button" href="?repoq&id=<?=$id;?>" class="btn btn-warning">Report</a>
		<?php } ?>
		<?php if ($session->userCheck($user) || $session->adminCheck()) { ?>
			<a type="button" href="edit.php?id=<?= $id; ?>" class="btn btn-warning">Edit</a>
			<a type="button" href="?dlq=true&id=<?=$id;?>" class="btn btn-danger">Delete</a>
		<?php } ?>
		<hr>
		<form action="" method="POST">
			<div class="form-group">
				<textarea class="form-control" name="content" rows="3" placeholder="Add a new comment"></textarea>
			</div>
			<input type="hidden" name="post_id" class="form-control" value="<?= $id; ?>" >
			<input type="hidden" name="uid" class="form-control" value="<?= USER_ID; ?>" >
			<br>
			<button type="comment" name="comment" class="btn btn-success">Submit</button>
		</form>

		<hr>
		<h3>Comments: </h3>
		<?php 
			$comments = Comment::get_comments($id);
			if(count($comments) === 0) {
				echo "There is nothing here yet, be the first to comment!";
			} else {
				foreach ($comments as $comment):
					$voted = QNA::has_voted($comment->id, USER_ID);
					$votes = Comment::get_votes($comment->id); 
					$commenter = Student::find_by_id($comment->uid) ?: Staff::find_by_id($comment->uid);
					$self = $session->userCheck($commenter);
					?>
					<div class="jumbotron" id="<?= $comment->id; ?>">
						<span><b><a href="<?= BASE_URL."students/".$commenter->id; ?>/"><?= !$self ? $commenter->full_name() : "You";?></a></span></b>
						 <span>commented</span> 
						<span title=" <?= displayDate($comment->created, "M d, Y h:m");?>" style="border-bottom: dashed 1px black"> <?= get_timeago($comment->created, "M d, Y"); ?></span>
						<span><?= $comment->id; ?></span>
						<hr>
						<p> <?= $comment->content; ?> </p>
						<hr>
						<span><?=$votes;?> Points </span>
						<?php if($voted){ ?>
							<a type="button" href="?dnc=<?=$comment->id;?>&id=<?=$id;?>" class="btn btn-danger"> Downvote</a>
						<?php } elseif ($self) {?>
						<?php } else { ?>
							<a type="button" href="?upc=<?=$comment->id;?>&id=<?=$id;?>" class="btn btn-success"> Upvote</a>
						<?php 

							} ?>
						<?php if ($self || $session->adminCheck()) { ?>
							<a type="button" href="?dlc=<?=$comment->id;?>&id=<?=$id;?>" class="btn btn-danger"> Delete</a>
							<a type="button" href="edit.php?id=<?= $comment->id; ?>" class="btn btn-warning">Edit</a>
						<?php } ?>
						<?php if (!$self) { ?>
							<a type="button" href="?repoc=<?=$comment->id;?>&id=<?=$id;?>" class="btn btn-warning">Report</a>
						<?php } ?>
					</div>
				<?php endforeach; 
			}?>
	</div>
</div>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>