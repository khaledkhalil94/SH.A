$(function(){

var $username, $email;
var _form = $('.signup-form');

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
	revalidate : false,

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
				url: 'api/_auth.api.php',
				type: 'post',
				dataType: 'json',
				data: {'action' : 'signup_form_check', 'name' : $name, 'value' : $value},

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

		if($username !== false && $email !== false){
			$values = _form.form('get values');

			console.log($values);

			$.ajax({
				url: 'api/_auth.api.php',
				type: 'post',
				dataType: 'json',
				data: {'action': 'signup', 'values' : $values},

				success: function(data, status) {

					console.log(data);


				},
				error: function(xhr, desc, err) {
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
