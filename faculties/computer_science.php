<?php
require_once ("../classes/init.php");
$pageTitle = "Students";


?>
<body>
	<?php
	include (ROOT_PATH . 'inc/head.php'); 
	?>

	<div class="main">
		<div class="container section">
			<div class="wrapper" style="text-align:center;">
				<h2>Computer Science</h2>
				<img src="<?= BASE_URL."images/cs.png" ?>">
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam ratione a incidunt exercitationem, nobis, nemo quod optio laborum necessitatibus quos, accusamus nam. Cumque molestiae repellat amet aut explicabo, corporis quisquam!</p>
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