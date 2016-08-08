<?php 

// Allow access only via ajax requests
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' ) {
      
      header('Location:404.php');
}

// only the login page can access this file
if (strtolower(basename($_SERVER['HTTP_REFERER'])) != 'login.php' ) {

	header('Location:404.php');

}

$error = $_GET['msg'];
?>

<div class="ui negative  message">
	<i class="remove massive red circle icon" style="margin-right:0;"></i>
	<br><br><br>
	<div class="header">
		<h2>ERROR!</h2>
	</div><br>
	<div class="content"><?= $error; ?></div>
</div>

