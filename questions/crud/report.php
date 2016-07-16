<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/classes/init.php");
include (ROOT_PATH . 'inc/head.php');
$session->is_logged_in();
$id = sanitize_id($_GET['id']) ?: null;

if($post = QNA::find_by_id($id)){
	if(isset($_POST['submit'])){
		$content = trim($_POST['content']);
		if (!empty($content)) {
			if ($content == $post->content) $session->message("", "./question.php?id={$id}", "");
			if (QNA::report($post)){
				$session->message("Your question has been reported successfully!", "./question.php?id={$id}", "success");
			} else {
				$session->message("Something went wrong!", ".", "danger");
			}
		} else {
			$session->message("question can't be empty!", "", "danger");
		}
	}
} elseif($post = Comment::find_by_id($id)){
	if(isset($_POST['submit'])){
		$content = trim($_POST['content']);
		if (!empty($content)) {
			if ($content == $post->content) $session->message("", "./question.php?id={$id}", "");;
			if (QNA::report($post)){
				$session->message("Your comment has been reported successfully!", "./question.php?id={$id}", "success");
			} else {
				$session->message("Something went wrong!", ".", "danger");
			}
		} else {
			$session->message("Comment can't be empty!", "", "danger");
		}
	}
} else {
	$session->message("Page can't be found!", "/sha/templates/404.php", "danger");
}
?>
<body>
	<div class="container section">
	<?= msgs(); ?>
	<h3>Report question</h3>
		<form action="" method="POST">
			<div class="form-group">
				<textarea class="form-control" name="content" rows="7"></textarea>
			</div>
			<input type="hidden" name="id" class="form-control" value="<?= $id;?>" >
			<button type="submit" name="submit" class="btn btn-success">Submit</button>
			<a type="button" href="./question.php?id=<?= $id; ?>" class="btn btn-default">Cancel</a>
		</form>
	</div>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>
