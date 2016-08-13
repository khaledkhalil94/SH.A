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
			$values = _form.form('get values');

			$.ajax({
				url: 'ajax/_auth.php',
				type: 'post',
				dataType: 'json',
				//data: {'action': 'login', 'username' : $username, 'password' : $password},
				data: {'action': 'login', 'values' : $values},

				success: function(data, status) {

					if(data == "1"){ // success
						
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
						}, 2000)


					} else { // failure

						var error = data;

						console.log(error);

						_form.parents('.form.login').removeClass('loading');
						$('.ui.message').addClass('negative');
						$('.ui.message .header').text(error);

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

$(function(){

	var $username, $email;
	var _form = $('.signup-form');
	var $neg;

	_form.form({
		fields: {
			username: {
				identifier: 'username',
				rules: [
					{
						type   : 'empty',
						prompt : 'Username can\'t be empty.'
					},
					{
						type   : 'minLength[4]',
						prompt : 'Username must be between 4 and 15 characters.'
					},
					{
						type   : 'maxLength[15]',
						prompt : 'Username must be between 4 and 15 characters.'
					},
					{
						type   : 'regExp[/^[a-zA-Z0-9_]{4,15}$/]',
						prompt : 'Username may only contain alphanumeric characters or \'_\''
					}
				]
			},
			password: {
				identifier: 'password',
				rules: [
					{
						type   : 'minLength[4]',
						prompt : 'Password must be at least 4 characters long.'
					}
				]
			},
			repassword: {
				identifier: 'repassword',
				rules: [
					{
						type   : 'match[password]',
						prompt : 'Passwords don\'t match.'
					}
				]
			},
			email: {
				identifier: 'email',
				rules: [
					{
						type   : 'empty',
						prompt : 'Email can\'t be empty.'
					},
					{
						type   : 'email',
						prompt : 'E-mail is not valid.'
					}
				]
			}
		},

		inline : true,
		duration: 400,
		delay : 400,
		on     : 'blur',
		keyboardShortcuts : false,
		reValidate : false,

		onValid : function(){
			if(this[0].name == 'username' || this[0].name == 'email'){

				$name = this[0].name;
				$value = this[0].value;

				$loader = "<div id=\"loader\" class=\"ui active tiny inline loader\"></div>";
				$pos = "<i style=\"margin-top: 11px;\" class=\"check circle large green icon\"></i>";
				$neg = "<i style=\"margin-top: 11px;\" class=\"remove circle large red icon\"></i>";

				var $vis=false;
				var _this = $(this);

				// remove any status indicators before displaying the loader
				$('.'+$name+'-status').children().remove();
				

				// display the laoder if its' not there
				if($('.'+$name+'-status').find($('.loader')).length == 0) {
					_this.parents('.field').next().append($loader);
					
				}

				$.ajax({
					url: 'ajax/_auth.php',
					type: 'post',
					dataType: 'json',
					data: {'action' : 'form_check', 'name' : $name, 'value' : $value},

					success: function(data, status) {

						if(data.status == 'true') { // if unique
							_this.parents('.field').next().children().replaceWith($pos);

							if(data.field == 'username') {
								$username = true;
							}

							if(data.field == 'email') {
								$email = true;
							}

						} else {
							_this.parents('.field').next().children().replaceWith($neg);

							_form.form('add prompt', data.field, data.field+' is already taken.');

							if(data.field == 'username') {
								$username = false;
							}

							if(data.field == 'email') {
								$email = false;
							}

							return false;
						}


					},
					error: function(xhr, desc, err) {
						console.log(xhr);
						console.log("Details: " + desc + "\nError:" + err);
						_this.parents('.field').next().children().replaceWith($neg);
					}
				}); // end ajax call
			}

		},
		onInvalid : function(){
			$name = this[0].name;
			$('.'+$name+'-status').children().remove();
		},

		onSuccess : function(event){
			event.stopPropagation();
			event.preventDefault();

			_form.find('.error').removeClass('error').find('.prompt').remove();
			
			if($username !== false && $email !== false){
				$values = _form.form('get values');
				_form.parents('.form.sign-up').addClass('loading');

				$.ajax({
					url: 'ajax/_auth.php',
					type: 'post',
					dataType: 'json',
					data: {'action': 'signup', 'values' : $values},

					success: function(data, status) {

						if(data.data){ // success
							data = data.data;

							_form.parents('.form.sign-up').removeClass('loading');
							$('.ui.compact.warning.message').remove();
							$('#signup-hr').remove();
							_form.parents('.form.sign-up').load('ajax/inc/signup-success.php', data.id,
								function( response, status, xhr ) {
									if ( status == "error" ) {
										console.log(status);
										console.log(xhr);
									}
								});
							_form.remove();
						console.log('registered');
						window.setTimeout(function(){
							window.location = "/sha/";
						}, 4000)


						} else if(data.errors) { // failure

							var errors = data.errors;

							console.log(errors);

							_form.parents('.form.sign-up').removeClass('loading');
							
							$.each(errors, function(k, v){
								$('.'+k+'-status').children().remove();
								console.log(v);
								_form.form('add prompt', k, v);

							});
							return;

						}
						
					},
					error: function(xhr, desc, err) {
						_form.parents('.form.sign-up').removeClass('loading');
						console.log(xhr);
						console.log("Details: " + desc + "\nError:" + err);
					}
				}); // end ajax call

				return;
			} else {
				return false;
			}
		}

		});
});
