<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");
$session->is_logged_in() ? true : redirect_to_D("/sha/signup.php");
//$id = isset($_GET['id']) ? $_GET['id'] : null;
$id = USER_ID;
if(!$id){
	echo "User was not found!";
	redirect_to_D("/sha", 2);
}
$studentInfo = StudentInfo::find_by_id($id);
$session->userLock($studentInfo);
$student = Student::find_by_id($studentInfo->id);
$session->userLock($student);

if (isset($_POST['submit'])) {
	  if($student->update()){
	 	$session->message("Your information have been updated", USER_URL);
	 } else {
	 	$session->message($_SESSION['fail']['sqlerr'], USER_URL);
	 }
}

$section = "students";
$pageTitle = $student->id;
include (ROOT_PATH . "inc/head.php");
 ?>
<div class="container section">
<?php if(!empty($session->msg)):?>
<div class="alert alert-success" role="alert"> <?= $session->msg; ?></div>
<?php endif; ?>

<h2>Update your privacy options</h2>

<br>
	<div class="container">
		<div class="col-md-6">
		    <form action="<?php echo "editstudent.php?id=". $id ?>" method="POST">
				
				<label for="phoneNumber">Who can see your profile</label>
		        <select class="form-control" name="profile_privacy">
				  <option value="1" <?= $student->profile_privacy == "1" ? "selected" : null; ?>>Public</option>
				  <option value="2" <?= $student->profile_privacy == "2" ? "selected" : null; ?>>Only users</option>
				  <option value="0" <?= $student->profile_privacy == "0" ? "selected" : null; ?>>Only me</option>
				</select>
				<br>

		        <label for="phoneNumber">Who can see your e-mail</label>
		        <select class="form-control" name="email_privacy">
				  <option value="1" <?= $student->email_privacy == "1" ? "selected" : null; ?>>Public</option>
				  <option value="2" <?= $student->email_privacy == "2" ? "selected" : null; ?>>Only users</option>
				  <option value="0" <?= $student->email_privacy == "0" ? "selected" : null; ?>>Only me</option>
				</select>
				<br>

		        <label for="phoneNumber">Who can see your location</label>
		        <select class="form-control" name="country_privacy">
				  <option value="1" <?= $student->country_privacy == "1" ? "selected" : null; ?>>Public</option>
				  <option value="2" <?= $student->country_privacy == "2" ? "selected" : null; ?>>Only users</option>
				  <option value="0" <?= $student->country_privacy == "0" ? "selected" : null; ?>>Only me</option>
				</select>
				<br>

		        <label for="phoneNumber">Who can see your phone number</label>
		        <select class="form-control" name="phoneNumber_privacy">
				  <option value="1" <?= $student->phoneNumber_privacy == "1" ? "selected" : null; ?>>Public</option>
				  <option value="2" <?= $student->phoneNumber_privacy == "2" ? "selected" : null; ?>>Only users</option>
				  <option value="0" <?= $student->phoneNumber_privacy == "0" ? "selected" : null; ?>>Only me</option>
				</select>
				<br>

		        <label for="phoneNumber">Who can see your gender</label>
		        <select class="form-control" name="gender_privacy">
				  <option value="1" <?= $student->gender_privacy == "1" ? "selected" : null; ?>>Public</option>
				  <option value="2" <?= $student->gender_privacy == "2" ? "selected" : null; ?>>Only users</option>
				  <option value="0" <?= $student->gender_privacy == "0" ? "selected" : null; ?>>Only me</option>
				</select>
				<br>
				<input type="hidden" name="id" value="<?php echo $student->id ?>" />
		        <input type="submit" class="btn btn-primary" name="submit" value="Update" />
		        <a class="btn btn-default" href="<?= USER_URL; ?>" role="button">Cancel</a>
		    </form>
		</div>
    </div>
</div>
<?php
include (ROOT_PATH . 'inc/footer.php');
?>