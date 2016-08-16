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
		send_by = $('#send_by').val();

		if(value.length <= 0){
			console.log("msg can't be empty");
			return false;
		}
		_form.addClass('loading');

		$.ajax({
			url: '/sha/ajax/_messages.php',
			type: 'post',
			dataType: 'json',
			data: {'action': 'msg_send', 'value' : value, 'token' : token, 'send_to' : send_to, 'send_by' : send_by},

			success: function(data, status) {

				if(data.status == "1"){ // success

					_form.parent().load('/sha/ajax/inc/msg-send-success.php');

				} else { // failure
					console.log(data);
					_form.removeClass('loading');
					_form.parent().load('/sha/ajax/inc/msg-send-fail.php', 'msg='+data);

				}
				
			},
			error: function(xhr, desc, err) {
				_form.removeClass('loading');
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			}
		});

		return false;
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
			url: '/sha/ajax/_messages.php',
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
					$('#msg_msg').load('/sha/ajax/inc/msg-arch-success.php');

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
			url: '/sha/ajax/_messages.php',
			type: 'post',
			dataType: 'json',
			data: {'action': 'msg_block', 'msgID' : msg_id},

			success: function(data, status) {

				$msg = $('#msg_msg');
				if($msg.length) $msg.remove();
					
				if(data.status == "1"){ // success

					$('.container.section.messages').prepend('<div id=\'msg_msg\'></div>');
					$('#msg_msg').load('/sha/ajax/inc/user-block-success.php');

				} else { // failure

					
					$('.container.section.messages').prepend('<div id=\'msg_msg\'></div>');
					$('#msg_msg').load('/sha/ajax/inc/user-block-fail.php', 'msg='+data);
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
		url: '/sha/ajax/_messages.php',
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
		url: '/sha/ajax/_messages.php',
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






