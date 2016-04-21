<?php
require_once ("../classes/init.php");
if (isset($_GET['id'])) {
	$id = (int)$_GET["id"];
}

$studentInfo = StudentInfo::find_by_id($id);
$student = Student::find_by_id($studentInfo->id);

$faculty = $student->get_faculty($student->faculty_id);
$faculty = ucwords(str_replace("_", " ", $faculty));

if (empty($studentInfo)){
	echo "User was not found!";
	//header("Location: " . BASE_URL . "students/");
	exit;
} elseif(empty($student)){
	$session->message("Please update your information");
	header("Location: " . BASE_URL . "students/settings/editstudent.php?id=".$id);
}

$section = "students";
$pageTitle = $studentInfo->id;
include (ROOT_PATH . "inc/head.php");
include (ROOT_PATH . 'inc/header.php');
include (ROOT_PATH . 'inc/navbar.php');
 ?>
<div class="container">
<?php if(!empty($_SESSION['msg'])):?>
<div class="alert alert-success" role="alert"> <?= $_SESSION['msg']; ?></div>
<?php endif; ?>

<?php echo "Username: " . $studentInfo->username . "<br>";?>

	<div class="details row">
		<div class="col-md-5">
			<div class="image"><img src=<?php echo $student->id;?> alt=""></div>
		</div>
			<div class="col-md-6">
				<p><?php echo "Name: " . $student->full_name(); ?></p>
				<p><?php echo "ID: " . $student->id; ?></p>
				<p><?php echo "Address: " . $student->address; ?></p>
				<p><?php echo "Phone Number: " . $student->phoneNumber; ?></p>
				<?php if (!empty($faculty)): ?>
					<p><?php echo "Faculty: " . $faculty; ?></p>
				<?php endif ?>
			</div>
		<?php //if (isset($session->user_id)) {
				//if($student->id === $session->user_id){
		 ?>
		<a class="btn btn-default" href="<?php echo BASE_URL."students/settings/editstudent.php?id=".$id?>" role="button">Update your information</a>
		<a class="btn btn-default" href="<?php echo BASE_URL."students/settings/account.php"?>" role="button">Change account settings</a>
		<?php 	//}
			//} ?>
	</div>
</div>
<?php
include (ROOT_PATH . 'inc/footer.php');
?>