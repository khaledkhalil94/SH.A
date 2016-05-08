<?php
require_once ("../classes/init.php");
$session->is_logged_in() ? true : redirect_to_D("/sha/signup.php");
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
if(!$id){
	echo "User was not found!";
	redirect_to_D("/sha", 20);
}

$studentInfo = StudentInfo::find_by_id($id);
$session->userLock($studentInfo);
$student = Student::find_by_id($studentInfo->id);

$img_path = ProfilePicture::get_profile_pic($student);
$faculty = Student::get_faculty($student->faculty_id);

if (empty($studentInfo)){
	echo "User was not found!";
	//header("Location: " . BASE_URL . "students/");
	exit;
} elseif(empty($student)){
	$session->message("Please update your information");
	header("Location: " . BASE_URL . "students/settings/editstudent.php?id=".$id);
}
$pageTitle = $studentInfo->id;
include (ROOT_PATH . "inc/head.php");
 ?>
<div class="content">
<?= msgs(); ?>
	<div class="details row">
<?php if ($session->adminCheck()): ?>
	<a class="btn btn-default" href="<?= BASE_URL."staff/admin/students/student.php?id=".$id?>" style="float:right;" role="button">Edit user</a>
<?php endif ?>
		<div class="col-md-5">
			<div class="image"><img src="<?php echo $img_path;?>" alt="" style="width:278px;"></div>
		</div>
			<div class="col-md-6">
				<p><?= "Name: " . $student->full_name(); ?></p>
				<p><?= "ID: " . $student->id; ?></p>
				<p><?= "Address: " . $student->address; ?></p>
				<p><?= "Phone Number: " . $student->phoneNumber; ?></p>
				<?= !empty($faculty) ? "<p>Faculty :{$faculty}</p>" : null ?>
				<?php if ($session->userLock($studentInfo)): ?>
				<a class="btn btn-default" href="<?= BASE_URL."students/settings/editstudent.php?id=".$id?>" role="button">Update your information</a>
				<a class="btn btn-default" href="<?= BASE_URL."students/settings/account.php"?>" role="button">Change account settings</a>
				<?php endif; ?>
			</div>
	</div>
</div>
<?php
include (ROOT_PATH . 'inc/footer.php');
?>