<?php
require($_SERVER['DOCUMENT_ROOT'].'/src/init.php');

// Allow access only via ajax requests
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' ) {

      header('Location:404.php');
}


$id = $_GET['id'];
?>

<div class="ui center" style="text-align:center;">
	<i class="check massive green circle icon"></i>
	<br><br>
	<div class="header">
		<h2><a href="/questions/question.php?id=<?= $id ?>">Your story</a> has been submitted.</h2>
	</div>
</div>