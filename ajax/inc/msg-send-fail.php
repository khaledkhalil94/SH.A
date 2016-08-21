<?php 
require($_SERVER['DOCUMENT_ROOT'].'/sha/src/init.php');

// Allow access only via ajax requests
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' ) {
      
      header('Location:404.php');
}

$msg = $_GET['msg'];
?>

<div class="ui center" style="text-align:center;">
	<i class="remove circle big red icon"></i>
	<br><br>
	<div class="header">
		<p><?= $msg; ?></p>
	</div>
</div>