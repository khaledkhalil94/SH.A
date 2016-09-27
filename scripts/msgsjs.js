// moves a message from inbox to archive
$(function(){

	var pending = [];
	var tid = [];
	var delay = 8000;

	$('.messages .messages-list #msg_arch').click(function(event){
		event.stopImmediatePropagation();

		$p = $(this).closest('.message-row');

		msg_id = $p.attr('msg-id');

		var $msg = "\
				<li class='ui compact positive message' id='"+msg_id+"'>\
				<p>Message has been deleted...<span id='msg-undo'><b>undo</b></span></p>\
				</li>";

		$p.toggle(300);
		$('#msgs-msg').show();
		$('#msgs-msg').append($msg);
		

		msg_ajax('msg_hide', msg_id);


		pending.push(msg_id);

		$('.message#'+v).delay(delay).fadeOut(350);

	});


	// on undo click
	$(document).on('click', 'span#msg-undo', function(){
		uid = $(this).parents('li').attr('id');

		msg_ajax('msg_unhide', uid);

		$('#msgid-'+uid).toggle();
		$(this).closest('li.message').remove();

	});
});


// move a message from the archive to inbox
$(function(){
	$('.messages #undo-msg').click(function(event){
		event.stopImmediatePropagation();

		$p = $(this).closest('.message-row');

		msg_id = $p.attr('msg-id');


		$p.toggle(300);
		
		msg_ajax('msg_unhide', msg_id);
	});
});

// deletes a message forever
$(function(){
	$('.messages #msg_remove').click(function(event){
		event.stopImmediatePropagation();

		$p = $(this).closest('.message-row');

		msg_id = $p.attr('msg-id');


		$p.remove();
		
		msg_ajax('msg_remove', msg_id);
	});
});

// sends a message
$(function(){
	var working = false;

	$('#send_msg').submit(function(e){
		e.preventDefault();
		e.stopPropagation();

		_form = $(this);

		if(working) return;

		working = true;


		value = $('#msg_context').val();
		token = $('#msg_token').val();
		send_to = $('#send_to').val();

		if(value.length <= 0){
			console.log("msg can't be empty");
			return false;
		}
		_form.addClass('loading');

		$.ajax({
			url: '/sha/controllers/_messages.php',
			type: 'post',
			dataType: 'json',
			data: {'action': 'msg_send', 'value' : value, 'token' : token, 'send_to' : send_to},

			success: function(data, status) {

				if(data.status == "1"){ // success

					_form.parent().load('/sha/controllers/inc/msg-send-success.php');

				} else { // failure
					console.log(data);
					_form.removeClass('loading');
					_form.parent().load('/sha/controllers/inc/msg-send-fail.php', 'msg='+data);

				}
				
			},
			error: function(xhr, desc, err) {
				_form.removeClass('loading');
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			}
		});

		return;
	});
});

// moves a message from inbox to archive
$(function(){
	$('.messages .msg-main .msg-user_info #msg_arch').click(function(event){
		event.stopImmediatePropagation();

		msg_id = getUrlVars();
		msg_id = msg_id.msg;

		$('.dropdown').dropdown('hide');

		$.ajax({
			url: '/sha/controllers/_messages.php',
			type: 'post',
			dataType: 'json',
			data: {'action': 'msg_hide', 'msgID' : msg_id},

			success: function(data, status) {

				if(data.status == "1"){ // success
					$dp = "\
						<div class='item' id='msg_unarch'>\
							<a class='ui a'>unArchive</a>\
						</div>\
						<div class='item' id='post-edit'>\
							<a class='ui a'>Delete</a>\
						</div>";

					$('.container.section.messages').prepend('<div id=\'msg_msg\'></div>');
					$('#msg_msg').load('/sha/controllers/inc/msg-arch-success.php');

					$arch = "<i title=\"This message is archived\" class=\"archive icon\"></i>";
					$('.messages .msg-user_info div.time').after($arch);

					$('.msg-main .menu').children().remove();
					$('.msg-main .menu').append($dp);

				} else { // failure
					return data;
				}
				
			},
			error: function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			}
		});
	});
});

// move a message from the archive to inbox
$(function(){
	$(document).on('click', '.messages .msg-main .msg-user_info #msg_unarch', function(event){
		event.stopImmediatePropagation();

		msg_id = getUrlVars();
		msg_id = msg_id.msg;
		
		msg_ajax('msg_unhide', msg_id);

		location = '/sha/messages/?sh=inb';
		window.location.replace(location);
	});
});

// marks a message as unread
$(function(){
	$(document).on('click', '.messages .msg-main .msg-user_info #msg_unread', function(event){
		event.stopImmediatePropagation();

		msg_id = getUrlVars();
		msg_id = msg_id.msg;
		
		msg_ajax('msg_unread', msg_id);

		var url = '/sha/messages/';
		window.location = url;
	});
});

// blocks a user
$(function(){
	$('.messages .msg-main .msg-user_info #msg_block').click(function(event){
		event.stopImmediatePropagation();

		msg_id = getUrlVars();
		msg_id = msg_id.msg;
		$msg_v = false;

		$('.dropdown').dropdown('hide');

		$.ajax({
			url: '/sha/controllers/_messages.php',
			type: 'post',
			dataType: 'json',
			data: {'action': 'msg_block', 'msgID' : msg_id},

			success: function(data, status) {

				$msg = $('#msg_msg');
				if($msg.length) $msg.remove();
					
				if(data.status == "1"){ // success

					$('.container.section.messages').prepend('<div id=\'msg_msg\'></div>');
					$('#msg_msg').load('/sha/controllers/inc/user-block-success.php');

				} else { // failure

					
					$('.container.section.messages').prepend('<div id=\'msg_msg\'></div>');
					$('#msg_msg').load('/sha/controllers/inc/user-block-fail.php', 'msg='+data);
				}
				
			},
			error: function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			}
		});


	});
});

// unblock a user
$(function(){
	$('.messages #user_unblock').click(function(){

		_this = $(this);
		uid = _this.closest('.item').attr('user-id');

	$.ajax({
		url: '/sha/controllers/_messages.php',
		type: 'post',
		dataType: 'json',
		data: {'action': 'unblock', 'msgID' : uid},

		success: function(data, status) {

			if(data.status == "1"){ // success

				_this.closest('.item').fadeOut(250, function(){

					this.remove();
				});

			} else { // failure
				return data;
			}
			
		},
		error: function(xhr, desc, err) {
			console.log(xhr);
			console.log("Details: " + desc + "\nError:" + err);
		}
	});
	});
});

// function for different ajax calls
function msg_ajax(action, msgID){
	$.ajax({
		url: '/sha/controllers/_messages.php',
		type: 'post',
		dataType: 'json',
		data: {'action': action, 'msgID' : msgID},

		success: function(data, status) {

			if(data == "1"){ // success
			} else { // failure
				return data;
			}
			
		},
		error: function(xhr, desc, err) {
			console.log(xhr);
			console.log("Details: " + desc + "\nError:" + err);
		}
	});
}

$(function(){
	$('.messages-list tr').each(function() {
		$dateE = $(this).find('#msg_date');

		$dateE.text(moment($dateE.text()).fromNow());
	});
});

$(function(){
	$('#s_msg_date').text(moment($('#s_msg_date').text()).fromNow());
});


// search for a user
$(function(){
	var hasLabel = false;
	var user;

	var input = $('#msg_sendto input');

	function label(user, closable=true){
				label =
				"<a href='/sha/user/"+ user.id +"/' class='ui image label'>\
				<img src='"+ user.image +"'>\
				"+ user.title;

				if(closable) label += "<i class='delete icon'></i>";

				label += "</a>";
				
		return label;
	}
	vars = getUrlVars();

	if(typeof vars.un !== 'undefined') {

		q = '/sha/controllers/_messages.php';

		$.get(q, {'un' : vars.un}, 
			function(res){
				user = res.results[0];
				
				label = label(user, false);
				$(input).before(label);

				$(input).attr('type', 'hidden');
				$(input).next().toggle();

				$('#msg_compose').form('set value', 'send_to', user.id);

			}, 'json'); 


	} else {

	 	q = '/sha/controllers/_messages.php?un={query}';
		_search = $('.ui.search');

		_search.search({
			minCharacters : 2,
			apiSettings   : {
				url: q
				},
			onSelect : function(result){

				if(hasLabel) return;

				user = result;
				console.log(user);
				_search.search('cancel query');

				$(input).before(label(user));

				$(input).attr('type', 'hidden');
				$(input).next().toggle();

				hasLabel = true;
			}
		  });
	}


	$(document).on('click', '#msg_sendto .delete.icon', function(e){
		e.preventDefault();

		$(this).parent().remove();
		$(input).attr('type', 'text');
		$(input).attr('value', '');
		$(input).next().toggle();
		hasLabel = false;
	});
});


// compose a message
$(function(){

	_form = $('#msg_compose');

	_form.form({
		on: 'submit',
		inline: true,
		fields: {
		  send_to: {
			identifier  : 'send_to',
			rules: [
			  {
				type   : 'empty',
				prompt : 'Search for a user'
			  }
			]
		  },
		  content: {
			identifier  : 'content',
			rules: [
			  {
				type   : 'empty',
				prompt : 'Subject can\'t be empty.'
			  }
			]
		  }
		}
	  });

	var working = false;


	_form.submit(function(e){
		e.preventDefault();
		e.stopPropagation();

		valid = _form.form('is valid');
		if(!valid){
			console.log('form is not valid'); 
			return;
		}

		if(working) return;
		working = true;


		uid = $('.ui.search').search('get result').id | _form.form('get value', 'send_to');

		content = $('#msg_context').val();
		token = $('#msg_token').val();

		if(!uid) {

			_form.form('add prompt', 'send_to', 'No user was selected.');

			console.log('No user was selected.');
			working = false;
			return false;
		}

		if(content.length <= 0){
			console.log("msg can't be empty");
			working = false;
			return false;
		}

		_form.addClass('loading');

		$.ajax({
			url: '/sha/controllers/_messages.php',
			type: 'post',
			dataType: 'json',
			data: {'action': 'msg_send', 'value' : content, 'token' : token, 'send_to' : uid},

			success: function(data, status) {

				if(data.status == "1"){ // success

					_form.parent().load('/sha/controllers/inc/msg-sent-success.php');

				} else { // failure
					console.log(data);
					_form.removeClass('loading');
					_form.parent().load('/sha/controllers/inc/msg-send-fail.php', 'msg='+data.err);

				}
				
			},
			error: function(xhr, desc, err) {
				_form.removeClass('loading');
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			}
		});

		return;
	});
});