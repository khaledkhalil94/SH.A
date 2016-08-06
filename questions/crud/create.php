<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/classes/init.php");
include (ROOT_PATH . 'inc/head.php');
if (!$session->is_logged_in()) $session->message("You must log in to submit a new question.", "", "danger");

if(isset($_POST['submit'])){

	$_POST['uid'] = USER_ID;
	$title = $_POST['title'];
	$content = $_POST['content'];

	if (empty(trim($title)) && empty(trim($content))) $session->message("Title and content can't be empty!", "", "danger");

	if(strlen(trim($content)) < 15) $session->message("Content must be at least 15 characters long.", "", "danger");

	$_POST['id'] = mt_rand(10000,20000);
	$create_post = $QNA->create_user($_POST);
	if ($create_post) $session->message("Your question has been submitted successfully!", "../question.php?id={$create_post->id}", "success");
}
?>
<body>
	<div class="container section">
	<?= msgs(); ?>
	
		<div class="ui raised very padded segment">
			<h3>Create a new question</h3>
				<form class="ui form" action="" method="POST">
					<div class="field">
						<label>Title</label>
						<input type="text" name="title" placeholder="Title">

					</div>
						<div class="field">
							<label for="content">Content</label>
							<textarea name="content" rows="7"></textarea>
						</div>
						<div class="field">
							<label>Select section</label>
							<div class="ui selection dropdown">
							<input type="hidden" name="faculty_id">
								<i class="dropdown icon"></i>
								<div class="default text"></div>
								<div class="menu">
									<div class="item" data-value="4">General</div>
									<div class="item" data-value="1">Engineering</div>
									<div class="item" data-value="2">Computer Science</div>
									<div class="item" data-value="3">Medicine</div>
								</div>
							</div>
						</div>
					<br>
					<br>
					<button type="submit" name="submit" class="ui button green">Submit</button>
					<a type="button" href="../" class="ui button ">Cancel</a>
				<div class="ui error message"></div>
				</form>
		</div>

	</div>
	<script src='../scripts/create_script.js'></script>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>