$(function(){

	_form = $('.ui.feed-post.form');

	uid = $('.user.user-profile').attr('user-id');

	w = false;

	_form.submit(function(e){
		e.preventDefault();

		if(w) return;
		w = true;

		token = $("input[name='feed_token'").val();
		content = $("textarea[name='content'").val();

		_form.addClass('loading');

		$.ajax({
		 	url: '/sha/controllers/_profile.php',
		 	type: 'POST',
		 	dataType: 'json',
		 	data: {
					'action': 'feed',
					'user_id' : uid,
					'content':content,
					'token':token
				 	},

			success: function(data, status) {

				 if(data.status == true) { // success
				 	
				 	_form.removeClass('loading');
					id = data.id;
				 	output_comment(id);
				 	
				 } else { // fail

				 	_form.removeClass('loading');
				 	console.log(data);

				 }

				w = false;
				_form.removeClass('loading');
			},
			error: function(xhr, desc, err) {
				_form.removeClass('loading');
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);

			}
		});
			w = false;

	});
});

function output_comment(id){

	_form = $('.ui.feed-post.form');

	$.ajax({
	 	url: '/sha/controllers/_profile.php',
	 	type: 'get',
	 	dataType: 'json',
	 	data: {
				'action': 'get_post', 
				'id': id
				},

		success: function(data, status) {

			$comment = Comment(data);

				$('.ui.feed-post.form textarea').val('');
				$('#emptycmt').remove();
				$("#profile_feed_comments").prepend($comment);

			_form.removeClass('loading');

				$('.reply textarea').val('');
		},
		error: function(xhr, desc, err) {

			_form.removeClass('loading');
			console.log(xhr);
			console.log("Details: " + desc + "\nError:" + err);
		}
	}); // end ajax call

	function Comment(data){

		var $comment = "\
		<div class='item'>\
			<a class='ui tiny image' href='/sha/user/"+data.uid+"/'>\
				<img src='"+data.img_path+"'>\
			</a>\
			<div class='content'>\
				<a class='header' href='/sha/user/"+data.uid+"/'>\
					<span><h4>"+data.firstName+"</h4></span>\
				</a>\
				<span>\
					<h5 style='display:inline;'>\
						<a href='/sha/user/"+data.uid+"/'>@"+data.username+"</a>\
					</h5>\
				</span>\
				<div class='meta'>\
					<a href='/sha/user/posts/"+data.id+"/' class='time' id='post-date'>a few seconds ago</a>\
				</div>\
				<div class='description'>\
					"+data.content+"\
				</div><br>\
				<div class='extra'>\
					<span style='cursor:pointer;' class='likes'>\
						<i class='heart red icon'></i> 0\
					</span>\
					<a href='/sha/user/posts/"+data.id+"/' style='cursor:pointer;' class='comments'>\
						<i class='comments blue icon'></i> 0 comments\
					</a>\
				</div>\
			</div>\
		</div>";

		return $comment;
	}
}