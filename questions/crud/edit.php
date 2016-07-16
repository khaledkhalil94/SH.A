<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/classes/init.php");
include (ROOT_PATH . 'inc/head.php');
$session->adminLock();
$id = isset($_GET['id']) ? $_GET['id'] : null;


if(isset($_POST['submit'])){
	$content = trim($_POST['content']);
	if (!empty($content)) {
		if ($content == $post->content) $session->message("", "./question.php?id={$id}", "");
		if ($QNA->update($_POST)){
			QNA::query("UPDATE `questions` SET last_modified = CURRENT_TIME WHERE id = {$post->id}");
			$session->message("Your question has been updated successfully!", "./question.php?id={$id}", "success");
		} else {
			$session->message("Something went wrong!", ".", "danger");
		}
	} else {
		$session->message("question can't be empty!", "", "danger");
	}
}

?>
<body>
	<div class="container section">
	<?= msgs(); ?>
	<h3>Edit question</h3>
		<form action="" method="POST">
			<div class="form-group">
				<textarea class="form-control" name="content" rows="7"><?= $post->content; ?></textarea>
			</div>
			<input type="hidden" name="id" class="form-control" value="<?= $id;?>" >
			<button type="submit" name="submit" class="btn btn-success">Submit</button>
			<a type="button" href="./question.php?id=<?= $id; ?>" class="btn btn-default">Cancel</a>
		</form>
	</div>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>
