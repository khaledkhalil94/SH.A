<?php 
require($_SERVER['DOCUMENT_ROOT'].'/sha/src/init.php');

// Allow access only via ajax requests
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' ) {
      
      header('Location:404.php');
}

$msg = $_GET['msg'];
?>

<div class="ui icon message negative">
	<i class="remove circle icon tiny"></i>
	<div class="content">
		<p><?= $msg; ?></p>
	</div>
</div>
		