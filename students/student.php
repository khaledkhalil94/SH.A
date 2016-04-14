<?php
require_once ("../classes/init.php");
if (isset($_GET['id'])) {
	$id = intval($_GET["id"]);
}

$student = Student::find_by_id($id);
//$student = $_SESSION['object'];

if (empty($student)){
	header("Location: " . BASE_URL . "students/");
	exit;
}

$section = "students";
$pageTitle = $student->id;
include (ROOT_PATH . "inc/head.php");
include (ROOT_PATH . 'inc/header.php');
include (ROOT_PATH . 'inc/navbar.php');
 ?>

<div class="container">

	<div class="details row">
		<div class="col-md-5">
		<div class="image"><img src=<?php echo $student->id;?> alt=""></div>
		</div>
		<div class="col-md-6">
		<p><?php echo "Name: " . $student->full_name($student->id); ?></p>
		<p><?php echo "ID: " . $student->id; ?></p>
		<p><?php echo "Address: " . $student->address; ?></p>
		<p><?php echo "About: "; ?></p>
		</div>
	</div>
</div>
<?php
include (ROOT_PATH . 'inc/footer.php');
?>