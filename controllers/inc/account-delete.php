<?php 
require_once( $_SERVER["DOCUMENT_ROOT"] .'/sha/src/init.php');

// Allow access only via ajax requests
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' ) {
      
      header('Location:404.php');
}

// only the delete page can access this file
if (strtolower(basename($_SERVER['HTTP_REFERER'])) != '?st=dl' ) {

	header('Location:404.php');

}


?>

<div class="ui icon negative message">
	<i class="warning circle icon"></i>
	<div class="content">
		<div class="header" style="padding-bottom:0px;">
			Remember, there's no going back!
		</div>
	</div>
</div>
<hr>
<h3>Enter your email and password to confim this action.</h3>
<br>
<form id="confirm-acc-del" class="ui form" method="POST">

	<div class="field">
		<label for="email">email</label>
		<input type="email" id="email" value="" />
	</div>

	<div class="field">
		<label for="password">Password</label>
		<input type="password" id="password" value="" />
	</div>
	<input type="hidden" name="auth_token" value="<?= Token::generateToken(); ?>" />
	<br><hr>
	<input type="submit" class="ui button red" value="Delete" />
	<a type="button" class="ui button basic" href="<?= USER_URL; ?>">Cancel</a>
</form>

<script>
$('#confirm-acc-del').submit(function(e){
	e.preventDefault();
	
	$('form').addClass('loading');

	$email = $('#email').val();
	$pw = $('#password').val();

	$.ajax({
		url: '/sha/controllers/_account.php',
		type: 'post',
		dataType: 'json',
		data: {'action' : 'delete_acc', 'email' : $email, 'password' : $pw},

		success: function(data){
			if(data == 1){

				$sucMsg = "\
				<div class=\"ui icon success message\">\
				<i class=\"warning circle icon\"></i>\
				<div class=\"content\">\
				<div class=\"header\" style=\"padding-bottom:0px;\">\
				Your account has been deleted.\
				</div>\
				</div>\
				</div>";

				$('form').removeClass('loading');
				$('.negative.message').replaceWith($sucMsg);

				window.setTimeout(function(){
					window.location = "/sha/";
				}, 2000)

			} else {

				$errMsg = "\
				<div class=\"ui icon negative message\">\
				<i class=\"warning circle icon\"></i>\
				<div class=\"content\">\
				<div class=\"header\" style=\"padding-bottom:0px;\">"
				+ data + "\
				</div>\
				</div>\
				</div>";

				$('form').removeClass('loading');
				$('.negative.message').replaceWith($errMsg);

			}
		},
		error: function(xhr, desc, err){

		}


	});
})
</script>