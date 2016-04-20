<?php
require_once ("../classes/init.php");

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
$student = Student::find_by_id($studentInfo->id);

if (isset($_POST['submit'])) {


    $studentInfo->username = $_POST['username'];
    $studentInfo->password = $_POST['password'];
    $studentInfo->email = $_POST['email'];
    $student->firstName = $_POST['firstName'];
    $student->lastName = $_POST['lastName'];
    $student->address = $_POST['address'];
    $student->phoneNumber = $_POST['phoneNumber'];


	  if($student->update() && $studentInfo->update()){
	 		$session->message("Your information have been updated");

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
	            <label for="username">Username</label>
	            <input type="text" class="form-control" name="username" value="<?php echo $studentInfo->username ?>"/>
	        </div>

	        <div class="form-group">
	            <label for="password">Password</label>
	            <input type="pwd" class="form-control" name="password" value="<?php echo $studentInfo->password ?>" />
	        </div>

	        <div class="form-group">
	            <label for="email">email</label>
	            <input type="email" class="form-control" name="email" value="<?php echo $studentInfo->email ?>" />
	        </div>

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

	        <input type="submit" class="btn btn-primary" name="submit" value="Update" />
	        <a class="btn btn-default" href="<?php echo $student->id ?>/" role="button">Cancel</a>
	    </form>
	   </div>
</div>
<?php
include (ROOT_PATH . 'inc/footer.php');
?>