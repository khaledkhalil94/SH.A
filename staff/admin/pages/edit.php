<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/classes/init.php");
include (ROOT_PATH . 'inc/head.php');
$session->adminLock();
$id = isset($_GET['id']) ? $_GET['id'] : null;
$content = Faculty::find_by_id($id) ?: $session->message("Page was not found!", "/sha/404.php");
if(isset($_POST['submit'])){
	echo "<pre>";
	print_r($_POST);
	echo "</pre>";
	if (!empty(trim($_POST['title'])) && !empty(trim($_POST['content']))) {
		if ($faculty->updatea($_POST)){
			$session->message("Your article has been updated successfully!", "./articles.php", "success");
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
	<h3>Edit Page</h3>
		<div class="row">
			<div class="col-md-8">
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
					<br>
					<p><b>Change department</b></p>
					<select class="form-control" name="faculty_id">
						<option <?= ($content->faculty_id == "0") ? "selected" : null; ?> value="0">Public</option>
						<option <?= ($content->faculty_id == "1") ? "selected" : null; ?> value="1">Engineering</option>
						<option <?= ($content->faculty_id == "2") ? "selected" : null; ?> value="2">Computer Science</option>
						<option <?= ($content->faculty_id == "3") ? "selected" : null; ?> value="3">Medicine</option>
					</select>
					<br>
					<p><b>Change Article Type</b></p>
					<select class="form-control" name="type">
						<option <?= ($content->type == "article") ? "selected" : null; ?> value="article">Article</option>
						<option <?= ($content->type == "news") ? "selected" : null; ?> value="news">News</option>
						<option <?= ($content->type == "main") ? "selected" : null; ?> value="main">Main Content</option>
					</select>
					<br>
					<p><b>Change Status</b></p>
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
					<a type="button" href="./articles.php" class="btn btn-default">Cancel</a>
				</form>
			</div>
			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">Info</div>
					<div class="panel-body">
						<p><b>Page created by: </b><?= $content->author; ?></p>
						<p><b>Created on: </b><?= displayDate($content->created, "d-m-Y")." at ".displayDate($content->created, "H:i"); ?></p>
						<p><b>Last modified on: </b><?= displayDate($content->last_modified, "d-m-Y")." at ".displayDate($content->last_modified, "H:i"); ?> (<?= get_timeago($content->last_modified); ?>)</p>
						<p><b>Current status: </b><?= $content->status ? $greenIcon : $redIcon; ?></p>
						<hr>
						<a type="button" href="delete.php?id=<?= $id; ?>" class="btn btn-danger">Delete page</a>
					</div>
				</div> 
			</div>
		</div>
	</div>
	</div>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>
