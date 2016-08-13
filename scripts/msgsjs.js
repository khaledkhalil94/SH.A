$(function(){

	var pending = [];
	var tid = [];
	var delay = 4000;

	$('.messages #msg_arch').click(function(event){
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
		
		pending.push(msg_id);
		$.each(pending, function(k, v){

			// fadeout the side messages
			$('.message#'+v).delay(delay).fadeOut(350);

			// confirm the deletion after delay
			t = window.setTimeout(function(){
				msg_ajax('msg_hide', v);
			}, delay);

			pending.splice(0, 1)
		});

		tid[msg_id] = t;
	});

	// on undo click
	$(document).on('click', 'span#msg-undo', function(){
		$uid = $(this).parents('li').attr('id');
		
		t = tid[$uid];

		$('#msgid-'+$uid).toggle();
		$(this).closest('li.message').remove();

		window.clearTimeout(t);

		delete tid[$uid];
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



function msg_ajax(action, msgID){
	$.ajax({
		url: '/sha/ajax/_messages.php',
		type: 'post',
		dataType: 'json',
		data: {'action': action, 'msgID' : msgID},

		success: function(data, status) {

			if(data == "1"){ // success
				

			} else { // failure


			}
			
		},
		error: function(xhr, desc, err) {
			console.log(xhr);
			console.log("Details: " + desc + "\nError:" + err);
		}
	});
}

