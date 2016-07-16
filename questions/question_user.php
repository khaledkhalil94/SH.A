<?php
// The view for the users
$pageTitle = "Question/User";
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
$user = Student::find_by_id($q->uid);
$self = $q->uid === USER_ID;

// TODO: BREAK EACH FUNCTION IN A SEPARATE FILE
if (isset($_GET['dlq'])) QNA::delete($q);

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
include (ROOT_PATH . 'inc/head.php');
$name = !$self ? $user->full_name() : "You";
$fac = array_search($q->faculty_id, $faculties);
?>
<script>
var $postID = <?= $_GET['id'];?>;
var $userID = <?= USER_ID;?>;
var $imgPath = "<?= ProfilePicture::get_profile_pic(Student::find_by_id(USER_ID));?>";
</script>
<body>
	<div class="container section">
		<?= msgs(); ?>

		<?php require_once('question_body_user.php'); ?>
		<div class="commentz section">
		<?php require_once('question_comments_user.php'); ?>
		</div>
	</div>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>