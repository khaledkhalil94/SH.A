<?php
require_once ("src/init.php");
$pageTitle = "Home Page";
$sec = "index";
include (ROOT_PATH . "inc/head.php"); 

?>
<body>
	<div class="container section">
		<div class="content">

			<?= msgs(); ?>
			<?php include (ROOT_PATH . 'inc/body/carousel.php') ?>
			<br>
			<br>
			<br>
			<div class="ui icon button">
				<i class="add icon"></i>
			</div>
		</div>
	</div>
	<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>