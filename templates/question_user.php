<?php
// The view for the users
$pageTitle = "Question/User";
include (ROOT_PATH . 'inc/head.php');
$id = sanitize_id($_GET['id']) ?: null;
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
$self = $q->uid === USER_ID;

// questions
if (isset($_GET['upq'])) QNA::upvote($q, USER_ID);
if (isset($_GET['dnq'])) QNA::downvote($id, USER_ID);
if (isset($_GET['dlq'])) QNA::delete($q);
// comments
if (isset($_POST['comment'])) Comment::comment();
if (isset($_GET['upc'])) {
	$comment = Comment::find_by_id($_GET['upc']);
	QNA::upvote($comment, USER_ID);
}
if (isset($_GET['dnc'])) if (QNA::downvote($_GET['dnc'], USER_ID)) $session->message("Downvoted.", "", "success");
if (isset($_GET['dlc'])) Comment::delete(Comment::find_by_id($_GET['dlc']));
$voted = QNA::has_voted($id, USER_ID);

$votes_count = QNA::get_votes($id) ?: "0";

$post_date = displayDate($q->created, "M d, Y h:m");
$post_dateAgo = get_timeago($q->created, "M d, Y");

$post_modified_date = displayDate($q->last_modified, "M d, Y h:m");
$post_modified_dateAgo = get_timeago($q->last_modified, "M d, Y");

if($q->last_modified > $q->created){
	$edited = "<span title=\"$post_modified_date\"> (edited $post_modified_dateAgo)</span>";
} else {
	$edited = "";
}
$isReported = QNA::reports("questions", $id);
$reports_count = QNA::get_reports("questions", $id) ? QNA::get_reports("questions", $id)[0]->count : null; 
$reports_count = $reports_count > 1 ? "This post has been reported ".$reports_count." times." : ($reports_count === NULL ? NULL : "This post has been reported once.");
?>
<body>
	<div class="container section">
		<?= BackBtn(); ?>
		<?= msgs(); ?>
			<div class="row">
				<div class="col-sm-8 blog-main">
					<div class="blog-post">
						<span><b><?= !$self ? $user->full_name() : "You";?></b> asked a question in  <?= array_search($q->faculty_id, $faculties) ?></span><br>
						<div class="time" title="<?= $post_date; ?>"><?= $post_dateAgo; ?><?= $edited; ?></div>
						<?php if($session->adminCheck()) {?>
						<a style="color:red;" href="/sha/staff/admin/questions/report.php?id=<?= $id; ?>"><?= $reports_count; ?></a>
						<?php } ?>
						<h3 class="blog-post-title"><?= $q->title; ?></h3>
						<hr>
						<p style="min-height:320px;"><?= $q->content; ?></p>
					</div>
				</div>
				<div class="col-sm-3 col-sm-offset-1 blog-sidebar" style="border-left: 1px #e2e2e2 solid;">
					<div class="sidebar-module sidebar-module-inset">
						<h4>Related questions</h4><hr>
						<?= QNA::sidebar_content($q) ?: "There's nothing here :("; ?>
				</div>
			</div>
		</div>
		<span><i style="color:red;" class="fa fa-heart fa-5" title="points" aria-hidden="true"></i> <?= $votes_count; ?></span>
		<?php if($voted){ ?>
			<a type="button" href="?dnq&id=<?=$id;?>" class="btn btn-danger"> Downvote</a>
		<?php } else {?>
			<a type="button" href="?upq&id=<?=$id;?>" class="btn btn-success"> Upvote</a>
		<?php } ?>
		<?php if ($session->is_logged_in() && !$session->userCheck($user)) { ?>
			<a type="button" href="report.php?id=<?=$id;?>" class="btn btn-warning">Report</a>
		<?php } ?>
		<?php if ($session->userCheck($user) || $session->adminCheck()) { 
				if ($session->adminCheck()) { ?>
					<a type="button" href="/sha/staff/admin/questions/edit.php?id=<?= $id; ?>" class="btn btn-warning">Edit</a>
		<?php	} else { ?>
				<a type="button" href="edit.php?id=<?= $id; ?>" class="btn btn-warning">Edit</a>
		<?php	} ?>
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
		<?php $comments = Comment::get_comments($id); ?>
		<h3>Comments (<?= count($comments); ?>): </h3>
		<?php if(count($comments) === 0) {
				echo "There is nothing here yet, be the first to comment!";
			} else {
				foreach ($comments as $comment):
					$voted = QNA::has_voted($comment->id, USER_ID);
					$votes = Comment::get_votes($comment->id); 
					$commenter = Student::find_by_id($comment->uid) ?: Staff::find_by_id($comment->uid);
					$self = $comment->uid === USER_ID;
					$reports_count = QNA::get_reports("comments", $comment->id) ? QNA::get_reports("comments", $comment->id)[0]->count : null; 
					$reports_count = $reports_count > 1 ? "This comment has been reported ".$reports_count." times." : ($reports_count === NULL ? NULL : "This comment has been reported once.");
					$comment_date = displayDate($comment->created, "M d, Y h:m");
					$comment_dateAgo = get_timeago($comment->created, "M d, Y");

					$comment_modified_date = displayDate($comment->last_modified, "M d, Y h:m");
					$comment_modified_dateAgo = get_timeago($comment->last_modified, "M d, Y");

					if($comment->last_modified > $comment->created){
						$edited = " <span class=\"time\" title=\"$comment_modified_date\">(edited $comment_modified_dateAgo)</span>";
					} else {
						$edited = "";
					}
					?>
					<div class="jumbotron" id="<?= $comment->id; ?>">
						<span><b><a href="<?= BASE_URL."students/".$commenter->id; ?>/"><?= !$self ? $commenter->full_name() : "You";?></a></span></b>
						 <span>commented</span> 
						<span title="<?=$comment_date;?>" style="border-bottom: dashed 1px black"> <?=$comment_dateAgo;?></span><?= $edited; ?>
						<?php if($session->adminCheck()) {?>
						<span><?= $comment->id; ?></span> <a style="color:red;" href="/sha/staff/admin/questions/reports.php#id=<?= $id; ?>">
						 <?= $reports_count; ?></a>
						<?php } ?>
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
							<a type="button" href="report.php?id=<?=$comment->id;?>" class="btn btn-warning">Report</a>
						<?php } ?>
					</div>
				<?php endforeach; 
			}?>
	</div>
</div>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>