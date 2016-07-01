<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/classes/init.php");
include (ROOT_PATH . 'inc/head.php');
$session->adminLock();
$id = isset($_GET['id']) ? $_GET['id'] : null;
$content = Faculty::find_by_id($id);
if(isset($_POST['submit'])){
	if (!empty(trim($_POST['title'])) && !empty(trim($_POST['content']))) {
		if ($faculty->update($_POST)){
			$session->message("Your article has been updated successfully!", "./articles.php?id={$id}", "success");
		} else {
			$session->message("Something went wrong!", ".", "danger");
		}
	} else {
		$session->message("Title and content can't be empty!", "", "danger");
	}
}
?>
<body>
	<div class="container section">
	<?= msgs(); ?>
	<h3>Edit article</h3>
		<form action="" method="POST">
			<div class="form-group">
				<label for="title">Article Title</label>
				<input type="hidden" name="id" value="<?php echo $content->id ?>" />
				<input type="title" name="title" class="form-control" id="title" value="<?= $content->title; ?>" placeholder="Title">
			</div>
			<div class="form-group">
				<label for="content">Article Content</label>
				<textarea class="form-control" name="content" rows="7"><?= $content->content; ?></textarea>
			</div>
			<input type="hidden" name="author" class="form-control" value="staff" >
			<input type="id" name="author" class="form-control" value="<?= $id; ?>" >
			<br>
			<p><b>Choose department</b></p>
			<select class="form-control" name="faculty_id">
				<option <?= ($content->faculty_id == "0") ? "selected" : null; ?> value="0">Public</option>
				<option <?= ($content->faculty_id == "1") ? "selected" : null; ?> value="1">Engineering</option>
				<option <?= ($content->faculty_id == "2") ? "selected" : null; ?> value="2">Computer Science</option>
				<option <?= ($content->faculty_id == "3") ? "selected" : null; ?> value="3">Medicine</option>
			</select>
			<br>
			<p><b>Choose Article Type</b></p>
			<select class="form-control" name="type">
				<option <?= ($content->type == "article") ? "selected" : null; ?> value="article">Article</option>
				<option <?= ($content->type == "news") ? "selected" : null; ?> value="news">News</option>
				<option <?= ($content->type == "main") ? "selected" : null; ?> value="main">Main Content</option>
			</select>
			<br>
			<p><b>Choose Status</b></p>
			<div class="radio">
				<label>
				<input <?= ($content->status == "1") ? "checked" : null; ?> type="radio" name="status" value="1">
					Published
				</label>
				<label>
				<input <?= ($content->status == "2") ? "checked" : null; ?> type="radio" name="status" value="2">
					Not Published
				</label>
				<label>
				<input <?= ($content->status == "3") ? "checked" : null; ?> type="radio" name="status" value="3">
					 Marked for deletion
				</label>
			</div>
			<br>
			<button type="submit" name="submit" class="btn btn-success">Submit</button>
			<a type="button" href="./articles.php?id=<?= $id; ?>" class="btn btn-default">Cancel</a>
		</form>
	</div>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>
