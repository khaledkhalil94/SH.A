<?php 

// Allow access only via ajax requests
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' ) {
      
      header('Location:404.php');
}

// only the signup page can access this file
if (strtolower(basename($_SERVER['HTTP_REFERER'])) != 'signup.php' ) {

	header('Location:404.php');

}

$id = array_shift(array_keys($_GET));
?>

<div class="ui center" style="text-align:center;">
	<i class="check massive green circle icon"></i>
	<br><br>
	<div class="header">
		<h2>Thank you for signing up!</h2>
	</div>
	<p>Please head over to your <a href="/sha/user/<?= $id; ?>/">profile</a> and update your informations.</p>
</div>