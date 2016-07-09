<?php
require_once ("classes/init.php");
$pageTitle = "Welcome!";

require(ROOT_PATH . 'inc/head.php'); 
?>

</pre>
<body>
	<div class="main">
		<div class="container">
			<?= msgs(); ?>
		<div class="jumbotron">
			<p>Thank you <?= $session->username; ?> for signing up, please head over to your <a href="<?= USER_URL; ?>"/>profile</a> and update your informations.</p>
		</div>

		</div>
	</div>
	<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>