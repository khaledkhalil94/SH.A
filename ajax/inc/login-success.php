<?php 
require($_SERVER['DOCUMENT_ROOT'].'/sha/classes/init.php');

// Allow access only via ajax requests
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' ) {
      
      header('Location:404.php');
}

// only the login page can access this file
if (strtolower(basename($_SERVER['HTTP_REFERER'])) != 'login.php' ) {

	header('Location:404.php');

}

?>

<div class="ui center" style="text-align:center;">
	<i class="check massive green circle icon"></i>
	<br><br>
	<div class="header">
		<h2>Welcome back <?= $_SESSION['username']; ?>!</h2>
	</div>
</div>