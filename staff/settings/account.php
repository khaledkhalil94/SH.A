<?php
require_once ("../../classes/init.php");

if (!isset($session->user_id)) {
	header("Location: " . BASE_URL . "index.php");
}

$id = (int)$session->user_id;

$userInfo = StaffInfo::find_by_id($id);

if (isset($_POST['submit'])) {


    $userInfo->username = $_POST['username'];
    $userInfo->password = $_POST['password'];
    $userInfo->email = $_POST['email'];



	if($userInfo->update()){
		$session->message("Your information have been updated");
		//header("Location: " . BASE_URL . "students/".$session->user_id."/");
	} else {
		echo $_SESSION['fail']['sqlerr'];
	}


}

$section = "students";
$pageTitle = $userInfo->id;
include (ROOT_PATH . "inc/head.php");
include (ROOT_PATH . 'inc/header.php');
include (ROOT_PATH . 'inc/navbar.php');
 ?>
<div class="container section">
<?php if(!empty($session->msg)):?>
<div class="alert alert-success" role="alert"> <?= $session->msg; ?></div>
<?php endif; ?>



<h2>Update your account settings</h2>
<h4><?php echo "Username: ";?><?php echo $userInfo->username; ?></h4>
<br>
	<div class="jumbotron">
	    <form action="<?php echo "account.php" ?>" method="POST">
	        <div class="form-group">
	            <label for="username">Username</label>
	            <input type="text" class="form-control" name="username" value="<?php echo $userInfo->username ?>"/>
	        </div>

	        <div class="form-group">
	            <label for="password">Password</label>
	            <input type="pwd" class="form-control" name="password" value="<?php echo $userInfo->password ?>" />
	        </div>

	        <div class="form-group">
	            <label for="email">email</label>
	            <input type="email" class="form-control" name="email" value="<?php echo $userInfo->email ?>" />
	        </div>


	        <input type="submit" class="btn btn-primary" name="submit" value="Update" />
	        <a class="btn btn-default" href="<?php echo BASE_URL."staff/professor.php?id=".USER_ID; ?>/" role="button">Cancel</a>
	    </form>
	   </div>
</div>
<?php
include (ROOT_PATH . 'inc/footer.php');
?>