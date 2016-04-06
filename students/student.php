<?php
  require_once ("../config/config.php");
  require_once (ROOT_PATH . 'students/info.php');
if (isset($_GET['id'])) {
	$student_id = intval($_GET["id"]);
	$student = get_student_single($student_id);
}

if (empty($student)){
	header("Location: " . BASE_URL . "students/");
	exit;
}

$section = "students";
$pageTitle = $student["name"];
include (ROOT_PATH . "inc/head.php");
include (ROOT_PATH . 'inc/header.php');
include (ROOT_PATH . 'inc/navbar.php');
 ?>

<div class="container">

	<div class="details row">
		<div class="col-md-5">
		<div class="image"><img src=<?php echo BASE_URL . "images/" . $student["img"];?> alt=""></div>
		</div>
		<div class="col-md-6">
		<p><?php echo "Name: " . $student["name"]; ?></p>
		<p><?php echo "ID: " . $student["sku"]; ?></p>
		<p><?php echo "Address: " . $student["address"]; ?></p>
		<p><?php echo "About: "; ?></p>
		</div>
	</div>
</div>
<?php
include (ROOT_PATH . 'inc/footer.php');
?>