<?php
require_once ("classes/init.php");
$search_term = "";
if (isset($_GET["s"])){
	$search_term = trim($_GET["s"]);
	if ($search_term != ""){
		require_once (ROOT_PATH . "user/info.php");
		$students = get_students_search($search_term);

	}
}



 $pageTitle = "Search";
 include (ROOT_PATH . 'inc/head.php'); 
 ?>
<body>
<?php 
$search_term = "";
if (isset($_GET["s"])){
	$search_term = trim($_GET["s"]);
	if ($search_term != ""){
		require_once ("../user/info.php");
		$students = get_students_search($search_term);

	}
}

?>
<div class="section shirts search page">
		<div class="wrapper">
			<h1>Search</h1>
			<form method="get" action="./">
				<input type="text" name="s" placeholder="Search by name of ID" value="<?php echo htmlspecialchars($search_term)?>">
				<input type="submit" value="Go">
			</form>

			<?php 
				if ($search_term != ""){
					if (!empty($students)){
						echo '<ul class="students">';
						foreach ($students as $student) {
							echo displayHTML($student);
						}

						echo '</ul>';
					}
					 else {
						echo "</p>No results were found!</p>";
					}
				}

			 ?>

		</div>

	</div>


<?php include (ROOT_PATH . 'inc/footer.php') ?>