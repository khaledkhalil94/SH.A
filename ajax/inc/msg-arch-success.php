<?php 
require($_SERVER['DOCUMENT_ROOT'].'/sha/classes/init.php');

// Allow access only via ajax requests
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' ) {
      
      header('Location:404.php');
}

?>

<div class="ui icon message success">
	<i class="archive icon tiny"></i>
	<div class="content">
		<p>Message has been moved to <a href="/sha/messages/?sh=tr">archive</a>.</p>
	</div>
</div>
		