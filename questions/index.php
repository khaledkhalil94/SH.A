<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/classes/init.php");
$pageTitle = "Students";
include (ROOT_PATH . 'inc/head.php');
$section = isset($_GET['section']) ? $_GET['section'] : "";
$selected = "style=\"background-color: #44ff59;\"";
switch ($section) {
	case 'eng':
		$qs = QNA::get_content(1);
		$Count = count($qs);
		$eng = "style=\"background-color: #44ff59;\"";
		break;
	case 'cs':
		$qs = QNA::get_content(2);
		$Count = count($qs);
		$cs = "style=\"background-color: #44ff59;\"";
		break;
	case 'md':
		$qs = QNA::get_content(3);
		$Count = count($qs);
		$md = "style=\"background-color: #44ff59;\"";
		break;
	default:
		$qs = QNA::get_content();
		$Count = count($qs);
		$css = "style=\"background-color: #44ff59;\"";
		break;
}
//if (!isset($id)) exit("404 NOT FOUND!");
$heart = "<i style=\"color:red;\" class=\"fa fa-heart fa-5\" title=\"points\" aria-hidden=\"true\"></i>";
$heartO = "<i style=\"color:red;\" class=\"fa fa-heart-o fa-5\" title=\"points\" aria-hidden=\"true\"></i>";
$comment = "<i style=\"margin-left: 10px;\" class=\"fa fa-comments-o\" aria-hidden=\"true\"></i>";
?>
<body>
	<div class="container section">
	<?php if ($session->is_logged_in()): ?>
		<div class="sortby">
			<p>Showing questions from: </p>
				<nav>
					<ul class="pager">
						<li><a <?= isset($css) ? $css : null; ?> href=".">All</a></li>
						<li><a <?= isset($eng) ? $eng :null;?> href="?section=eng"> Engineering</a></li>
						<li><a <?= isset($cs) ? $cs : null; ?> href="?section=cs"> Computer Science</a></li>
						<li><a <?= isset($md) ? $md : null; ?> href="?section=md"> Medicine</a></li>
					</ul>
				</nav>
			</div>
	<?php endif; ?>
	<?= msgs(); ?>
		<h3>Questions</h3>
		<!-- TODO: Add pagination -->
		<?php 
		if (count($qs) < 1) { echo "There are no questions in this section yet.<br>"; } else {;
		foreach ($qs as $q):
			$length = 150;
			$subject = ctrim($q->content, $length);
			if (strlen($q->content) > $length) {
				$subject .= "... <a href=\"question.php?id={$q->id}\"> Read More</a>";
			}
			$user = Student::find_by_id($q->uid) ?: Staff::find_by_id($q->uid);
			$self = $q->uid === USER_ID ?: false;
			$commentsCount = count(Comment::get_comments($q->id));
			$commentsCount = $comment." {$commentsCount}";
			$votes = QNA::get_votes($q->id);
			$votes = $votes > 0 ? $heart." {$votes}" : $heartO.$votes;
			$reports_count = QNA::get_reports("questions", $q->id) ? QNA::get_reports("questions", $q->id)[0]->count : null;
			$reports_count = $reports_count > 1 ? $reports_count." Reports" : ($reports_count === NULL ? "0 Reports" : "1 Report");
			?>
				<div class="jumbotron">
					<a href="../questions/question.php?id=<?php echo $q->id; ?>"><h3> <?= $q->title; ?> </h3></a>

					<span><b><a href="<?= BASE_URL."students/".$user->id; ?>/"><?= $self ? "You" : $user->full_name();?></a></b> asked a question</span>

					<span title="<?= displayDate($q->created, "M d, Y h:m"); ?>" style="border-bottom: dashed 1px black"><?= get_timeago($q->created, "M d, Y");?> </span> in <span><?= array_search($q->faculty_id, $faculties); ?></span>
					<hr>
					<p> <?= $subject; ?> </p>
					<br><br><br><hr>
					<!-- TODO: add Number of comments/points -->
					<span><?=$votes;?></span> <span><?= $commentsCount; ?></span>
					<?php if($session->adminCheck()): ?>
						<a style="color:red;" href="/sha/staff/admin/questions/report.php?id=<?= $q->id; ?>"> and <?= $reports_count; ?></a>
					<?php endif; ?>
				</div>
			<?php 
	 	endforeach; 
	 	}?>
		<a type="button" href="create.php" class="btn btn-default">Ask a new question</a>
	 	</div>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>
