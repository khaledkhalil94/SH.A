<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");
$session->adminLock();
$id = $_GET['id'];


$studentInfo = StudentInfo::find_by_id($id);
//$student = Student::find_by_id($studentInfo->id);

if (isset($_POST['submit'])) {


    $studentInfo->username = $_POST['username'];
    $studentInfo->password = $_POST['password'];
    $studentInfo->email = $_POST['email'];


	if($studentInfo->update()){
		$session->message("{$studentInfo->username}'s settings have been updated");
		header("Location: student.php?id=".$id);
	} else {
		echo $_SESSION['fail']['sqlerr'];
	}


}

$section = "students";
$pageTitle = $studentInfo->id;
include (ROOT_PATH . "inc/head.php");
 ?>
<div class="container section">
<?php if(!empty($session->msg)):?>
<div class="alert alert-success" role="alert"> <?= $session->msg; ?></div>
<?php endif; ?>



<h2>Update account settings</h2>
<h4><?php echo "Username: ";?><?php echo $studentInfo->username; ?></h4>
<br>
	<div class="jumbotron">
	    <form action="<?= "account.php?id=".$id ?>" method="POST">
	        <div class="form-group">
	            <label for="username">Username</label>
	            <input type="text" class="form-control" name="username" value="<?= $studentInfo->username ?>"/>
	        </div>

	        <div class="form-group">
	            <label for="password">Password</label>
	            <input type="pwd" class="form-control" name="password" value="<?= $studentInfo->password ?>" />
	        </div>

	        <div class="form-group">
	            <label for="email">email</label>
	            <input type="email" class="form-control" name="email" value="<?= $studentInfo->email ?>" />
	        </div>


	        <input type="submit" class="btn btn-primary" name="submit" value="Update" />
	        <a class="btn btn-default" href="<?= "student.php?id=".$id; ?>" role="button">Cancel</a>
	    </form>
	   </div>
</div>
<?php
include (ROOT_PATH . 'inc/footer.php');
?>