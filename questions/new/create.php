<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/src/init.php");
$sec = "questions";
$pageTitle="Ask a new question";
include (ROOT_PATH . 'inc/head.php');
if (!$session->is_logged_in()) $session->message("You must log in to submit a new question.", "", "danger");

$QNA = new QNA();
$sections = $QNA->get_sections();
?>
<body>
	<div class="container section">

		<div class="ui raised very padded segment">
			<h3>Create a new question</h3>
				<form class="ui form create_q" action="" method="POST">
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
							<input type="hidden" name="section">
							<i class="dropdown icon"></i>
							<div class="default text"></div>
							<div class="menu">
							<?php foreach($sections AS $section): ?>
								<div class="item section <?= $section['acronym']; ?>" data-value="<?= $section['id']; ?>"><?= $section['title']; ?></div>
							<?php endforeach; ?>
							</div>
						</div>
					</div>
					<input type="hidden" name="token" value="<?= Token::generateToken(); ?>">
					<div class="ui toggle checkbox q_status">
						<input type="checkbox" name="public">
						<label>Public</label>
					</div>
					<br>
					<br>
					<button type="submit" name="submit" class="ui button green">Submit</button>
					<a type="button" href="../" class="ui button ">Cancel</a>
				<div class="ui error message"></div>
				</form>
		</div>

	</div>
	<script src='<?= BASE_URL ?>scripts/q_create.js'></script>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>