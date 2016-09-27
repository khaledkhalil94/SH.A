$('.form.create_q').form({
	fields: {
		title: {
			identifier: 'title',
			rules: [
			{
				type   : 'empty',
				prompt : 'Title can\'t be empty'
			}
			]
		},
		content: {
			identifier: 'content',
			rules: [
			{
				type   : 'empty',
				prompt : 'Content can\'t be empty'
			},
			{
				type   : 'minLength[1]',
				prompt : 'Content must be at least 10 characters long'
			}
			]
		},
		section: {
			identifier: 'section',
			rules: [
			{
				type   : 'minCount[1]',
				prompt : 'Please select a section'
			}
			]
		},
		public: {
			identifier: 'public',
			optional : true
		}
	},
	inline : true,
	duration: '15',
	on     : 'change',
});

$('.ui.selection.dropdown').dropdown('set text', 'Choose a section');
$('.checkbox.q_status').checkbox('set checked');

$('.form.create_q').submit(function(e){
	e.preventDefault();

	_form = $(this);
	title = $('input[name=title]').val();
	content = $('textarea[name=content]').val();
	token = $('input[name=token]').val();
	section = $('#q_c_dropdown').dropdown('get value');
	status = $('.checkbox.q_status').checkbox('is unchecked') ? "2" : "1";

	valid = _form.form('is valid');
	if(!valid) return false;

	_form.addClass('loading');

	$.ajax({
		url : '/sha/controllers/_question.php',
		type : 'post',
		dataType : 'json',
		data : {'action' : 'create', 'title' : title, 'content' : content, 'section' : section, 'token' : token, 'status' : status},

		success: function(data){
			if(data.status == true){
				_form.removeClass('loading');

				_form.parent().load('/sha/controllers/inc/msg-send-success.php', 'id='+data.id);

			} else {

				data.err;
				_form.removeClass('loading');
			}
		},
		error: function(xhr){
			console.log(xhr);
			_form.removeClass('loading');
			return false;
		}

	})
	return false;
});









