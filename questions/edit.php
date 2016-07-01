<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/classes/init.php");
include (ROOT_PATH . 'inc/head.php');
$session->adminLock();
$id = isset($_GET['id']) ? $_GET['id'] : null;

if($comment = QNA::find_by_id($id)){
	if(isset($_POST['submit'])){
		if (!empty(trim($_POST['content']))) {
			if ($QNA->update($_POST)){
				$session->message("Your question has been updated successfully!", "./question.php?id={$id}", "success");
			} else {
				$session->message("Something went wrong!", ".", "danger");
			}
		} else {
			$session->message("question can't be empty!", "", "danger");
		}
	}
} elseif($comment = Comment::find_by_id($id)){
	if(isset($_POST['submit'])){
		if (!empty(trim($_POST['content']))) {
			if ($comment->update($_POST)){
				$session->message("Your comment has been updated successfully!", "./question.php?id={$id}", "success");
			} else {
				$session->message("Something went wrong!", ".", "danger");
			}
		} else {
			$session->message("Comment can't be empty!", "", "danger");
		}
	}
}
?>
<body>
	<div class="container section">
	<?= msgs(); ?>
	<h3>Edit question</h3>
		<form action="" method="POST">
			<div class="form-group">
				<textarea class="form-control" name="content" rows="7"><?= $comment->content; ?></textarea>
			</div>
			<input type="hidden" name="id" class="form-control" value="<?= $id;?>" >
			<button type="submit" name="submit" class="btn btn-success">Submit</button>
			<a type="button" href="./question.php?id=<?= $id; ?>" class="btn btn-default">Cancel</a>
		</form>
	</div>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>
