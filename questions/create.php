<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/classes/init.php");
include (ROOT_PATH . 'inc/head.php');
if (!$session->is_logged_in()) $session->message("You must log in to submit a new question.", "", "danger");
if(isset($_POST['submit'])){
	if (!empty(trim($_POST['title'])) && !empty(trim($_POST['content']))) {
		$_POST['id'] = mt_rand(10000,20000);
		$create_post = $QNA->create_user($_POST);
		if ($create_post) $session->message("Your question has been submitted successfully!", "question.php?id={$create_post->id}", "success");
	} else {
		$session->message("Title and content can't be empty!", "", "danger");
	}
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
			<input type="hidden" name="uid" class="form-control" value="<?=USER_ID;?>" >
			<br>
			<p><b>Choose department</b></p>
			<select class="form-control" name="faculty_id">
				<option value="1">Engineering</option>
				<option value="2">Computer Science</option>
				<option value="3">Medicine</option>
			</select>
			<br>
			<br>
			<button type="submit" name="submit" class="btn btn-success">Submit</button>
			<a type="button" href="." class="btn btn-default">Cancel</a>
		</form>
	</div>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>