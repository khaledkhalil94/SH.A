<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");
$session->is_logged_in() ? true : redirect_to_D("/sha/signup.php");
$id = USER_ID;


$user = Student::get_user_info($id);
$session->userLock($user);


 ?>

<div class="ui delete padded segment">
	<div class="ui dividing header">
		ACCOUNT DELETE
	</div>
	<div class="ui segment vertical">
		<div class="ui negative message">
			<div class="header" style="padding-bottom:0px;">
				Warning!
				<div class="ui clearing divider"></div>
			</div>
			If you delete your account, everything associated with it will be forever gone.
			<br> Are you sure about this ?<br><br>
		</div>

		<hr><br>
		<button id="acc_del" class="ui red button" />Delete</button>
		<a type="button" class="ui button basic" href="<?= USER_URL; ?>">Cancel</a>
	</div>
</div>
