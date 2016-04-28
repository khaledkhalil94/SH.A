<?php
require_once ("../classes/init.php");
$pageTitle = "Students";


?>
<body>
	<?php
	include (ROOT_PATH . 'inc/head.php'); 
	include (ROOT_PATH . 'inc/header.php');
	include (ROOT_PATH . 'inc/navbar.php');
	?>

	<div class="main">
		<div class="container section">
			<div class="wrapper">
				<h2>Computer Science</h2>
				<ul class="students">
					<h3>Professors and teachers</h3>
					<?php Professor::display_prof(2); ?>
				</ul>
				<ul class="students">
					<h3>Students registered in Computer Science</h3>
					<?php Student::display_students(2); ?>
				</ul>
			</div>
		</div>
	</div>

	<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>