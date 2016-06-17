<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");
$session->adminLock();
$id = isset($_GET['id']) ? $_GET['id'] : null;

if(!$id){
	redirect_to_D("/sha", 2);
}


$studentInfo = StudentInfo::find_by_id($id);
$student = Student::find_by_id($id);

if (isset($_POST['submit'])) {

	    switch ($_POST['faculty_id']) {
	    	case 'Engineering':
	    		$_POST['faculty_id'] = "1";
	    		break;

	    	case 'Computer Science':
	    		$_POST['faculty_id'] = "2";
	    		break;    

	    	case 'Medicine':
	    		$_POST['faculty_id'] = "3";
	    		break;
	    
	    	default:
	    		$_POST['faculty_id'] = "0";
	    		break;
	    }

	  if($student->update()){
	 	$session->message("{$studentInfo->username}'s information have been updated", "./students.php");
	 } else {
	 	$session->message("Something went wrong!", "./students.php", "warning");
	 }


}
?>
<?php 
$img_path = ProfilePicture::get_profile_pic($student);
$has_pic = (bool)$student->has_pic;
$ProfilePicture->has_pic = $has_pic;
$ProfilePicture->id = $id;
 ?>
<?php // Upload or Change profile picture
if (isset($_POST['upload'])) {
	if (empty($_FILES['userfile']['name'])) {
		echo "Please select a valid photo";
	} else {
		$has_pic ? Student::update_pic($_POST) : Student::upload_pic($_POST);
		$session->message("Profile picture has been updated.", "editstudent.php?id={$id}");
	}
}
?>
<?php if (isset($_POST['delete'])) { 
		Student::delete_pic();
		$session->message("Profile picture has been deleted.", "editstudent.php?id={$id}");
	   }
?>
<?php
$section = "students";
$pageTitle = $student->id;
include (ROOT_PATH . "inc/head.php");
 ?>
<div class="container section">
<?= msgs(); ?>

<h2>Update information</h2>

	<div class="container">
		<div class="col-md-4">
		 	<div class="image"><img src=<?= $img_path; ?> alt="" style="width:228px;"></div>
				<br>
				<a class="btn btn-default" id="chgpic" role="button">Edit picture</a>
					<form style="display:none;" id="form" enctype="multipart/form-data" action="<?php echo "editstudent.php?id=". $id ?>" method="POST">
					    <input type="hidden" name="MAX_FILE_SIZE" value=<?= MAX_PIC_SIZE ?> />
					    <input name="userfile" type="file" />
					    <input type="hidden" name="id" value="<?= $student->id ?>" />
					    <input type="submit" name="upload" value="Upload" />
					</form>
								
					<?php if($has_pic): ?>
						<form style="display:none;" id="form" action="<?php echo "editstudent.php?id=". $id ?>" method="POST">
							<input type="submit" name="delete" class="btn btn-danger" value="Delete Picture" />
						</form>
						<a class="btn btn-default" id="prwp" href="<?= "previewpic.php?id=".$id; ?>" role="button">Preview picture</a>
					<?php endif; ?>
				<script>
					$( "#chgpic" ).click(function() {
						$( "#form" ).show( 0, function() {
							$( "#chgpic" ).hide( 0, function() {
								$( "#prwp" ).hide( 0, function() {
								});
							});
						});
					});
				</script>
		</div>

		<div class="col-md-6">
		    <form action="<?= "editstudent.php?id=". $id ?>" method="POST">
				
		        <div class="form-group">
		            <label for="firstName">First Name</label>
		            <input type="firstName" class="form-control" name="firstName" value="<?php echo $student->firstName ?>" />
		        </div>

		        <div class="form-group">
		            <label for="lastName">Last Name</label>
		            <input type="lastName" class="form-control" name="lastName" value="<?php echo $student->lastName ?>" />
		        </div>

		        <div class="form-group">
		            <label for="address">Address</label>
		            <input type="address" class="form-control" name="address" value="<?php echo $student->address ?>" />
		        </div>

		        <div class="form-group">
		            <label for="phoneNumber">Phone Number</label>
		            <input type="phoneNumber" class="form-control" name="phoneNumber" value="<?php echo $student->phoneNumber ?>" />
		            <input type="hidden" name="id" value="<?php echo $student->id ?>" />
		        </div>

		        <label for="phoneNumber">Select your faculty</label>
		        <select class="form-control" name="faculty_id">
				  <option <?php if ($student->faculty_id == "1") {echo "selected";} ?>>Engineering</option>
				  <option <?php if ($student->faculty_id == "2") {echo "selected";} ?>>Computer Science</option>
				  <option <?php if ($student->faculty_id == "3") {echo "selected";} ?>>Medicine</option>
				</select>
				<br>
		        <input type="submit" class="btn btn-primary" name="submit" value="Update" />
		        <a class="btn btn-default" href="<?= "student.php?id=".$id; ?>" role="button">Cancel</a>
		    </form>
		</div>
    </div>
</div>
<?php
include (ROOT_PATH . 'inc/footer.php');
?>