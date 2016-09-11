<?php
// The view for the users
$pageTitle = "Question/User";
$id = sanitize_id($_GET['id']) ?: null;

$QNA = new QNA();

if(!$q = QNA::get_question($id)) {
	// if the id is not in the questions database, try to find it in the comment database.
	if ($q = Comment::getComment($id)) { 
		$q = $q['post_id'];
		if($q == $id) Redirect::redirectTo('404');
		Redirect::redirectTo(BASE_URL."questions/question.php?id={$q}#{$id}");
	} else {
		Redirect::redirectTo('404');
	}
}

if($q->status != 1 && !($session->adminCheck() || $session->userCheck($q->uid))) Redirect::redirectTo('404');

$user = User::get_user_info($q->uid);

$self = $q->uid === USER_ID;

$voted = QNA::has_voted($id, USER_ID);

$votes_count = QNA::get_votes($id) ?: "0";

$post_date = $q->created;
$post_modified_date = $q->last_modified;

if($q->last_modified > $q->created){
	$edited = " (edited <span id='post-date-ago' title=\"$post_modified_date\">$post_modified_date</span>)";
} else {
	$edited = "";
}
//$isReported = QNA::reports("questions", $id);

$reports_count = QNA::get_reports("questions", $id) ? count(QNA::get_reports("questions", $id)) : null; 
$reports_count = $reports_count > 1 ? $reports_count." times." : ($reports_count === NULL ? NULL : "1 time.");
include (ROOT_PATH . 'inc/head.php');
$name = $q->full_name;
$imgPath = $q->img_path ?: DEF_PIC;
?>
<body>
	<div class="question-page ui container section">
		<?= msgs(); ?>

		<?php require_once('question_body_user.php'); ?>
		<div class="commentz section">
		<?php require_once('question_comments_user.php'); ?>
		</div>
	</div>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>