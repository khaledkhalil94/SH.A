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

// if((int)$session->user_id !== (int)$id){
// 	header("Location: " . BASE_URL . "index.php");
// }

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



<h2>Update your information</h2>
<h4><?php echo "Username: ";?><?php echo $studentInfo->username; ?></h4>
<br>
	<div class="jumbotron">
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
<?php
include (ROOT_PATH . 'inc/footer.php');
?>