<?php
require_once ("../classes/init.php");

if (!isset($session->user_id)) {
	header("Location: " . BASE_URL . "index.php");
}

$id = (int)$session->user_id;


$studentInfo = StudentInfo::find_by_id($id);
//$student = Student::find_by_id($studentInfo->id);

if (isset($_POST['submit'])) {


    $studentInfo->username = $_POST['username'];
    $studentInfo->password = $_POST['password'];
    $studentInfo->email = $_POST['email'];



	if($studentInfo->update()){
			$session->message("Your information have been updated");

	} else {
		echo $_SESSION['fail']['sqlerr'];
	}


}

$section = "students";
$pageTitle = $studentInfo->id;
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


	        <input type="submit" class="btn btn-primary" name="submit" value="Update" />
	        <a class="btn btn-default" href="<?php echo $studentInfo->id ?>/" role="button">Cancel</a>
	    </form>
	   </div>
</div>
<?php
include (ROOT_PATH . 'inc/footer.php');
?>