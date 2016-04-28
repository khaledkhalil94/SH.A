<?php
require_once ("../../classes/init.php");

// if (!isset($session->user_id)) {
// 	header("Location: " . BASE_URL . "index.php");
// }
//$id = (int)$session->user_id;
$id = $_GET['id'];

if ($_GET['id'] != $id) {
	header("Location: " . BASE_URL . "students/editstudent.php?id=".$id);
}


$studentInfo = StudentInfo::find_by_id($id);
$student = Student::find_by_id($id);

if (isset($_POST['submit'])) {
	    $student->firstName = $_POST['firstName'];
	    $student->lastName = $_POST['lastName'];
	    $student->address = $_POST['address'];
	    $student->phoneNumber = $_POST['phoneNumber'];

	    switch ($_POST['faculty_id']) {
	    	case 'Engineering':
	    		$student->faculty_id = "1";
	    		break;

	    	case 'Computer Science':
	    		$student->faculty_id = "2";
	    		break;    

	    	case 'Medicine':
	    		$student->faculty_id = "3";
	    		break;
	    
	    	default:
	    		$student->faculty_id = "0";
	    		break;
	    }

	  if($student->update()){

	 	$session->message("Your information have been updated");
	 	//header("Location: " . BASE_URL . "students/".$session->user_id."/");

	 } else {
	 	echo $_SESSION['fail']['sqlerr'];
	 }


}

$section = "students";
$pageTitle = $student->id;
include (ROOT_PATH . "inc/head.php");
include (ROOT_PATH . 'inc/header.php');
include (ROOT_PATH . 'inc/navbar.php');
 ?>
<div class="container section">
<?php if(!empty($session->msg)):?>
<div class="alert alert-success" role="alert"> <?= $session->msg; ?></div>
<?php endif; ?>

<?php 
	$ProfilePicture = new ProfilePicture($studentInfo->type);
	$ProfilePicture->id = $id;
	$img_path = $ProfilePicture->get_profile_pic($id);
 ?>

<h2>Update your information</h2>

<br>
	<div class="container">
		<div class="col-md-4">
			
			<?php // if the user has no picture at all
			 if(empty($img_path)){ ?>
			<p>Upload profile picture</p>
	 		<?php	if (isset($_POST['upload'])) {
	 					if (empty($_FILES['userfile']['name'])) {
	 						echo "Please select a valid photo";
	 					} else {
							$ProfilePicture->upload_pic();
							header("Refresh:0");
	 					}
					}
				} else { //use already has a profile picture ?>
				<div class="image"><img src=<?php echo $img_path;?> alt="" style="width:228px;"></div>
				<p>Change your profile picture</p>
		 		<?php
			 		if (isset($_POST['upload'])) {
			 			if (empty($_FILES['userfile']['name'])) {
	 						echo "Please select a valid photo";
	 					} else {
							$ProfilePicture->update_pic();
							header("Refresh:0");
					}
				}
			}
				 ?>
			<form enctype="multipart/form-data" action="<?php echo "editstudent.php?id=". $id ?>" method="POST">
			    <!-- MAX_FILE_SIZE must precede the file input field -->
			    <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
			    <!-- Name of input element determines name in $_FILES array -->
			    <input name="userfile" type="file" />
			    <input type="submit" name="upload" value="Upload" />
			</form>
			<br>

			<?php if(!empty($img_path)):
			  ?>

				<form action="<?php echo "editstudent.php?id=". $id ?>" method="POST">
				<?php if (isset($_POST['delete'])) { 
					$ProfilePicture->delete_pic();
					
					    }?>
					<input type="submit" name="delete" class="btn btn-secondary" value="Delete Picture" />
				</form>
			<?php endif; ?>
		</div>

		<div class="col-md-6">
		    <form action="<?php echo "editstudent.php?id=". $id ?>" method="POST">
				
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
		        </div>

		        <label for="phoneNumber">Select your faculty</label>
		        <select class="form-control" name="faculty_id">
				  <option <?php if ($student->faculty_id == "1") {echo "selected";} ?>>Engineering</option>
				  <option <?php if ($student->faculty_id == "2") {echo "selected";} ?> >Computer Science</option>
				  <option <?php if ($student->faculty_id == "3") {echo "selected";} ?>>Medicine</option>
				</select>
				<br>
		        <input type="submit" class="btn btn-primary" name="submit" value="Update" />
		        <a class="btn btn-default" href="<?php echo BASE_URL."students/".USER_ID; ?>/" role="button">Cancel</a>
		    </form>
		</div>
    </div>
</div>
<?php
include (ROOT_PATH . 'inc/footer.php');
?>