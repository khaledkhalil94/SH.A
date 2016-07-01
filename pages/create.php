<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/classes/init.php");
include (ROOT_PATH . 'inc/head.php');
$session->adminLock();
if(isset($_POST['submit'])){
	if (!empty(trim($_POST['title'])) && !empty(trim($_POST['content']))) {
		if ($faculty->create_user($_POST)) $session->message("Your article has been submitted successfully!", ".", "success");
	} else {
		$session->message("Title and content can't be empty!", "", "danger");
	}
	

	// echo "<pre>";
	// print_r($_POST);
	// echo "</pre>";
}
?>
<body>
	<div class="container section">
	<?= msgs(); ?>
	<h3>Create a new article</h3>
		<form action="" method="POST">
			<div class="form-group">
				<label for="title">Title</label>
				<input type="title" name="title" class="form-control" id="title" placeholder="Title">
			</div>
			<div class="form-group">
				<label for="content">Content</label>
				<textarea class="form-control" name="content" rows="7"></textarea>
			</div>
			<input type="hidden" name="author" class="form-control" value="staff" >
			<br>
			<p><b>Choose department</b></p>
			<select class="form-control" name="faculty_id">
				<option value="0">Public</option>
				<option value="1">Engineering</option>
				<option value="2">Computer Science</option>
				<option value="3">Medicine</option>
			</select>
			<br>
			<p><b>Choose Article Type</b></p>
			<select class="form-control" name="type">
				<option value="article">Article</option>
				<option value="news">News</option>
				<option value="main">Main Content</option>
			</select>
			<br>
			<p><b>Choose Status</b></p>
			<div class="checkbox">
				<label>
				<input type="checkbox" name="status" value="0">
					Not Published
				</label>
			</div>
			<br>
			<button type="submit" name="submit" class="btn btn-success">Submit</button>
			<a type="button" href="." class="btn btn-default">Cancel</a>
		</form>
	</div>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>
