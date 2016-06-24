<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/classes/init.php");
$pageTitle = "Students";
$id = 2;

?>
<body>
	<?php
	include (ROOT_PATH . 'inc/head.php'); 
	?>

	<div class="main">
		<div class="container section">
			<div class="wrapper" style="text-align:center;">
				<p><?= Faculty::display_content("main", $id); ?></p>

				<ul class="students">
					<h3>Staff</h3>
					<?php Staff::display_prof($id); ?>
				</ul>
				<ul class="students">
					<h3>Students registered in Computer Science</h3>
					<?php Student::display_students($id); ?>
				</ul>
			</div>
		</div>
	</div>
	<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>