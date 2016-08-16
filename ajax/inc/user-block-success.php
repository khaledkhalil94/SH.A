<?php 
require($_SERVER['DOCUMENT_ROOT'].'/sha/classes/init.php');

// Allow access only via ajax requests
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' ) {
      
      header('Location:404.php');
}

?>

<div class="ui icon message success">
	<i class="remove circle icon tiny"></i>
	<div class="content">
		<p>User has been blocked, you can manage your  <a href="/sha/messages/?sh=blc">blocklist</a> anytime later.</p>
	</div>
</div>
		