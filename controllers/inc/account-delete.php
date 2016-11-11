<?php 
require_once( $_SERVER["DOCUMENT_ROOT"] .'/src/init.php');

// Allow access only via ajax requests
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' ) {
	Redirect::redirectTo('404');
}

// only the delete page can access this file
if (strtolower(basename($_SERVER['HTTP_REFERER'])) != '?st=dl' ) {
	Redirect::redirectTo('404');

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
<h3>Enter your password to confim this action.</h3>
<br>
<form id="confirm-acc-del" class="ui form" method="POST">
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

	$pw = $('#password').val();
	$token = $('input[name=auth_token]').val();

	$.ajax({
		url: '/controllers/_account.php',
		type: 'post',
		dataType: 'json',
		data: {'action' : 'delete_acc', 'password' : $pw, 'token' : $token},

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
					window.location = "<?= BASE_URL ?>";
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