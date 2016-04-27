<?php
require_once ("../classes/init.php");
if (isset($_GET['id'])) {
	$id = (int)$_GET["id"];
}
$studentInfo = StaffInfo::find_by_id($id);
$student = Professor::find_by_id($studentInfo->id);

if($student->has_pic){
	$img_path = $student->get_profile_pic($student->id);
} else {
	$img_path = BASE_URL."images/profilepic/pp.png";
}

$faculty = substr($student->faculty_id,0,1);
$faculty = $student->get_faculty($faculty);
$faculty = ucwords(str_replace("_", " ", $faculty));

$department = substr($student->faculty_id,1,2);
$department = $student->get_faculty($department);
$department = ucwords(str_replace("_", " ", $department));

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
			<div class="image"><img src=<?php echo $img_path;?> alt="" style="width:278px;"></div>
		</div>
			<div class="col-md-6">
				<p><?php echo "Name: " . $student->firstName; ?></p>
				<p><?php echo "ID: " . $student->id; ?></p>
				<p><?php echo "Phone Number: " . $student->bio; ?></p>
				<?php if (!empty($faculty)): ?>
					<p><?php echo "Faculty: " . $faculty; ?></p>
				<?php endif ?>
				<?php if (!empty($department)): ?>
					<p><?php echo "Department: " . $department; ?></p>
				<?php endif ?>
				<?php //if (isset($session->user_id)) {
						//if($student->id === $session->user_id){
				 ?>
				<a class="btn btn-default" href="<?php echo BASE_URL."students/settings/editstudent.php?id=".$id?>" role="button">Update your information</a>
				<a class="btn btn-default" href="<?php echo BASE_URL."students/settings/account.php"?>" role="button">Change account settings</a>
				<?php 	//}
					//} ?>
			</div>
	</div>
</div>
<?php
include (ROOT_PATH . 'inc/footer.php');
?>