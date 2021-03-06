$('#update_settings').form({
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
				prompt : 'Username must be between 3 and 10 characters.'
			},
			{
				type   : 'maxLength[15]',
				prompt : 'Username must be between 3 and 10 characters.'
			},
			{
				type   : 'regExp[/^[a-zA-Z0-9_]{4,15}$/]',
				prompt : 'Username may only contain alphanumeric characters or \'_\''
			}
			]
		},
		old_password: {
			identifier: 'old_password',
			rules: [
			{
				type   : 'empty',
				prompt : 'Enter your current password to update settings.'
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
			}]
		}
	},
		inline : true,
		duration: '15',
		on     : 'change'
});

$('#user-links-form').form({
	fields: {
		website: {
			identifier: 'website',
			optional: true,
			rules: [
			{
				type   : 'url',
				prompt : 'Invalid link.'
			}
			]
		},
		skype: {
			identifier: 'skype',
			optional: true,
		},
		twitter: {
			identifier: 'twitter',
			optional   : true,
			rules: [
			{
				type   : 'url',
				prompt : 'Invalid link.'
			}
			]
		},
		github: {
			identifier: 'github',
			optional: true,
			rules: [
			{
				type   : 'url',
				prompt : 'Invalid link.'
			}
			]
		},
		facebook: {
			identifier: 'facebook',
			optional: true,
			rules: [
			{
				type   : 'url',
				prompt : 'Invalid link.'
			}
			]
		}
	},
	inline : true,
	duration: '15',
	keyboardShortcuts : false,
	on     : 'change'
});

$('#form_updInfo').form({
	fields: {
		firstName: {
			identifier: 'firstName',
			rules: [
			{
				type   : 'empty',
				prompt : 'Please enter your name'
			},
			{
				type   : 'minLength[3]',
				prompt : 'First name must be between 3 and 10 characters'
			},
			{
				type   : 'maxLength[10]',
				prompt : 'First name must be between 3 and 10 characters'
			},
			{
				type   : 'regExp[/^[a-zA-Z0-9^\._-]{3,10}$/]',
				prompt : 'Name is not valid'
			}
			]
		},
		lastName: {
			identifier: 'lastName',
			optional: true,
			rules: [
			{
				type   : 'minLength[3]',
				prompt : 'Last name must be between 3 and 10 characters'
			},
			{
				type   : 'maxLength[10]',
				prompt : 'Last name must be between 3 and 10 characters'
			},
			{
				type   : 'regExp[/^[a-zA-Z0-9^\._-]{3,10}$/]',
				prompt : 'Last name is not valid'
			}
			]
		},
		phoneNumber: {
			identifier: 'phoneNumber',
			optional   : true,
			rules: [
			{
				type   : 'number',
				prompt : 'Phone Number must be a number'
			},
			{
				type   : 'minLength[8]',
				prompt : 'Phone Number is not valid'
			},
			{
				type   : 'maxLength[16]',
				prompt : 'Phone Number is not valid'
			}
			]
		},
		gender: {
			identifier: 'gender',
			rules: [
			{
				type   : 'empty',
				prompt : 'Please select a gender'
			}
			]
		},
		about: {
			identifier: 'about',
			rules: [
			{
				type   : 'maxLength[60]',
				prompt : 'That\'s too long!'
			}
			]
		},
		address: {
			identifier: 'address',
			optional:true
		},

	},
	inline : true,
	duration: '15',
	keyboardShortcuts : false,
	on     : 'blur'
});