<?php
require_once ("../classes/init.php");
$id = sanitize_id($_GET['id']) ?: null;
if(!$id){
	echo "User was not found!";
	redirect_to_D("/sha", 20);
}

$studentInfo = StudentInfo::find_by_id($id);
if (empty($studentInfo))$session->message("Invalid user.", "/sha/404.php");

$student = Student::find_by_id($studentInfo->id);
if(empty($student)){
	$session->message("Please update your information");
	header("Location: " . BASE_URL . "students/settings/editstudent.php?id=".$id);
}

$img_path = ProfilePicture::get_profile_pic($student);
$faculty = Student::get_faculty($student->faculty_id);
$name = $student->full_name();
$id = $student->id;
$email = $studentInfo->email;
$location = $student->address;
$gender = $student->gender;
$phoneNumber = $student->phoneNumber;


$pageTitle = $id;
include (ROOT_PATH . "inc/head.php");
Student::profilePrivacy($student);
 ?>

<div class="content student">
<?= msgs(); ?>
<?php if (Messages::hasMsgs($id) && $session->userCheck($student)): ?>
		<div style="text-align:center; padding-top: 15px;">
			<span style="background-color:red; padding:10px;">
				<a href="../messages" style="color:white;"><?php Messages::Msgs($id); ?></a>
			</span>
		</div>
<?php endif; ?>

	<div class="details row">
		<?php if (!$session->userCheck($studentInfo) && $session->is_logged_in()): ?>
			<a style="float:right;" class="btn btn-default" href="<?= BASE_URL."students/messages/compose.php?to={$id}"?>" role="button">Send a private message</a>
		<?php endif; ?>
		<div class="col-md-5">
			<h4><?= "<b>" . $name . "</b>"; ?></h4>
			<div class="image"><img src="<?php echo $img_path;?>" alt="" style="width:278px;"></div>
			
		</div>

		<div class="col-md-6">
		<p><?= "ID: " . $id; ?></p>

		<?php if (Student::CheckPrivacy($student,$student->email_privacy) && !empty($email)): ?>
			<p><?= "E-Mail: " . $email; ?></p>
		<?php endif; ?>

		<?php if (Student::CheckPrivacy($student,$student->country_privacy) && !empty($location)): ?>
			<p><?= "Address: " . $location; ?></p>
		<?php endif; ?>

		<?php if (Student::CheckPrivacy($student,$student->phoneNumber_privacy) && !empty($phoneNumber)): ?>
			<p><?= "Phone Number: " . $phoneNumber; ?></p>
		<?php endif; ?>

		<?php if (Student::CheckPrivacy($student,$student->gender_privacy) && !empty($gender)): ?>
			<p><?= "Gender: " . $gender; ?></p>
		<?php endif; ?>

			<?= !empty($faculty) ? "<p>Faculty: {$faculty}</p>" : null ?>
			<?php if ($session->userCheck($studentInfo)): ?>
			<a class="btn btn-default" href="<?= BASE_URL."students/settings/editstudent.php"?>" role="button">Update your information</a>
			<a class="btn btn-default" href="<?= BASE_URL."students/settings/editprivacy.php"?>" role="button">Update your privacy</a>
			<a class="btn btn-default" href="<?= BASE_URL."students/settings/account.php"?>" role="button">Change account settings</a>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php
include (ROOT_PATH . 'inc/footer.php');
?>