$(function(){

var _form = $('.login-form');

_form.form({
	fields: {
		username: {
			identifier: 'username',
			rules: [
				{
					type   : 'empty',
					prompt : 'Username can\'t be empty.'
				}
			]
		},
		password: {
			identifier: 'password',
			rules: [
				{
					type   : 'empty',
					prompt : 'Password can\'t be empty.'
				}
			]
		}
	},

	onSuccess : function(event){
		event.stopPropagation();
		event.preventDefault();


		_form.parents('.form.login').addClass('loading');

		$username = _form.form('get value', 'username');
		$password = _form.form('get value', 'password');

		$.ajax({
			url: 'ajax/_auth.php',
			type: 'post',
			dataType: 'json',
			data: {'action': 'login', 'username' : $username, 'password' : $password},

			success: function(data, status) {

				if(data == "1"){ // success
					
					console.log('ayy');
					_form.parents('.form.login').removeClass('loading');
					$('.ui.message').remove();
					$('#login-hr').remove();
					_form.parents('.form.login').load('ajax/inc/login-success.php', $username,
						function( response, status, xhr ) {
							if ( status == "error" ) {
								console.log(status);
								console.log(xhr);
							}
						});
					_form.remove();

					window.setTimeout(function(){
						window.location = "/sha";
					}, 3000)


				} else { // failure

					var error = data;

					console.log(error);

					_form.parents('.form.login').removeClass('loading');
					$('.ui.message').remove();
					$('#login-hr').remove();

					_form.parents('.form.login').load('ajax/inc/login-failure.php', 'msg='+error);
					_form.remove();

					window.setTimeout(function(){
						window.location = "/sha";
					}, 6000)

				}
				
			},
			error: function(xhr, desc, err) {
				_form.parents('.form.sign-up').removeClass('loading');
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			}
		}); // end ajax call

		return;

	}

	});
});
