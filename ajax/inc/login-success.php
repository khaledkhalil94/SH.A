<?php 

// Allow access only via ajax requests
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' ) {
      
      header('Location:404.php');
}

// only the signup page can access this file
if (strtolower(basename($_SERVER['HTTP_REFERER'])) != 'login.php' ) {

	header('Location:404.php');

}

$name = array_shift(array_keys($_GET));
?>

<div class="ui center" style="text-align:center;">
	<i class="check massive green circle icon"></i>
	<br><br>
	<div class="header">
		<h2>Welcome back <?= $name ?>!</h2>
	</div>
</div>