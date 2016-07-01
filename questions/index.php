<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/classes/init.php");
$pageTitle = "Students";
include (ROOT_PATH . 'inc/head.php');

$qs = QNA::get_content();

//if (!isset($id)) exit("404 NOT FOUND!");
?>
<body>
	<div class="container section">
	<?php if ($session->is_logged_in()): ?>
		<a type="button" href="create.php" class="btn btn-default">Ask a new question</a>
	<?php endif; ?>
	<?= msgs(); ?>
		<h3>Questions</h3>
		<!-- TODO: Add pagination -->
		<?php 
		foreach ($qs as $q):
			$length = 150;
			$subject = ctrim($q->content, $length);
			if (strlen($q->content) > $length) {
				$subject .= "... <a href=\"question.php?id={$q->id}\"> Read More</a>";
			}
			$user = Student::find_by_id($q->uid) ?: Staff::find_by_id($q->uid);
			$self = $session->userCheck($user);
			$commentsCount = count(Comment::get_comments($q->id));
			$commentsCount = $commentsCount > 1 ? $commentsCount." Comments" : $commentsCount === 0 ? "0 Comments" : "1 Comment";
			$votes = QNA::get_votes($q->id);
			$votes = $votes > 1 ? $votes." Points" : ($votes === NULL ? "0 Points" : "1 Point");
			?>
				<div class="jumbotron">
					<a href="../questions/question.php?id=<?php echo $q->id; ?>"><h3> <?= $q->title; ?> </h3></a>

					<span><b><a href="<?= BASE_URL."students/".$user->id; ?>/"><?= !$self ? $user->full_name() : "You";?></a></b> asked a question</span>

					<span title="<?= displayDate($q->created, "M d, Y h:m"); ?>" style="border-bottom: dashed 1px black"><?= get_timeago($q->created, "M d, Y");?> </span> in <span><?= array_search($q->faculty_id, $faculties); ?></span>
					<hr>
					<p> <?= $subject; ?> </p>
					<br><br><br><hr>
					<!-- TODO: add Number of comments/points -->
					<span><?=$votes;?> and </span><span><?= $commentsCount; ?></span>
				</div>
			<?php 
	 	endforeach; ?>
	 	</div>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>
