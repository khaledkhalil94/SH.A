// post vote
$(function(){
	$('#votebtn').on('click', function(e){
		e.preventDefault();
		$btn = $(this)
		$('#votebtn').addClass("loading");
		if (!$btn.hasClass('voted')) { // if not upvoted
			$.ajax({
				url: './crud/vote.php',
				type: 'post',
				data: {'vote': {'action':'post-upvote','id': $postID}},
				success: function(data, status) {

					if(status == "success") {
						$($btn).removeClass("loading grey");
						$($btn).addClass("red voted");
						$('#votescount').removeClass("grey");
						$('#votescount').addClass("red");
						$('#votebtn').find("span").text("unLike");
						var votescount = parseInt($('#votescount').text()) + 1;
						$('#votescount').text(votescount);
					}
				},
				error: function(xhr, desc, err) {
					console.log(xhr);
					console.log("Details: " + desc + "\nError:" + err);
				}
			}); // end ajax call
		} else {
			$.ajax({
				url: './crud/vote.php',
				type: 'post',
				data: {'vote': {'action':'post-downvote','id': $postID}},
				success: function(data, status) {

					if(status == "success") {
						$('#votescount').removeClass("red");
						$($btn).removeClass("red voted loading");
						$($btn).addClass("grey");
						$('#votescount').addClass("grey");
						$('#votebtn').find("span").text("Like");
						var votescount = parseInt($('#votescount').text()) - 1;
						$('#votescount').text(votescount);
					}
				},
				error: function(xhr, desc, err) {
					console.log(xhr);
					console.log("Details: " + desc + "\nError:" + err);
				}
			}); // end ajax call
		}
	});
});

// comment votes
$(function(){
	$('.comment-vote-btn').on('click', function(e){
		e.preventDefault();
		e.stopPropagation();

		var $this = $(this);
		var $comment = $this.closest('.comment');
		var $id = $comment.attr('id');

		console.log($id);

		if (!$this.hasClass('voted')) { // if not upvoted
			$.ajax({
				url: './crud/vote.php',
				type: 'post',
				data: {'vote': {'action':'comment-upvote','id': $id}},

				// vote success
				success: function(data, status) {
					if(status == "success") {
						$comment.find($('.comment-vote-btn')).addClass("voted");
						$comment.find($('.comment-vote-btn i')).addClass("red");
						//$comment.find($('.comment-vote-btn')).text("unlike");

						var $votescount = parseInt($comment.find($('.comment-votes-count')).text());
						$votescount = isNaN($votescount) ? 1 : $votescount + 1;

						console.log($votescount);
						$comment.find($('.comment-votes-count')).text($votescount);
					}
				},
				error: function(xhr, desc, err) {
					console.log(xhr);
					console.log("Details: " + desc + "\nError:" + err);
				}
			}); // end ajax call
		} else {
			$.ajax({
				url: './crud/vote.php',
				type: 'post',
				data: {'vote': {'action':'comment-downvote','id': $id}},
				success: function(data, status) {

					if(status == "success") {
						$comment.find($('.comment-vote-btn')).removeClass("voted");
						$comment.find($('.comment-vote-btn i')).removeClass("red");
						//$comment.find($('.comment-vote-btn')).text("like");

						var $votescount = parseInt($comment.find($('.comment-votes-count')).text());

						$votescount = ($votescount == 1) ? '' : $votescount - 1;

						console.log($votescount);
						$comment.find($('.comment-votes-count')).text($votescount);
					}
				},
				error: function(xhr, desc, err) {
					console.log(xhr);
					console.log("Details: " + desc + "\nError:" + err);
				}
			}); // end ajax call
		}
	});
});

// comment submit
$(function(){
	$(".field textarea").focusin(function(){
		$('#subcomment').show();
	});

	$("#textarea").keyup(function(){
		var $this = $(this);
		var $value = $this.val();
		var len = $this.val().length;
		if(len > 0 && $value.trim() != ''){
			$('#subcomment').removeClass('disabled');
		}
		if(len < 1 || $value.trim() == ''){
			$('#subcomment').addClass('disabled');
		}
	});

	$('#subcomment').on('click', function(e){
		e.preventDefault();
		var $btn = $(this);
		var $content = $('textarea').val();
	 	$('.commentz form').addClass("loading");

	 $.ajax({
	 	url: './crud/comment.php',
	 	type: 'post',
	 	data: {'comment':
	 	{
	 		'content':$content,
	 		'post_id':$postID,
	 		'uid':$userID
	 	}
	 },
	 success: function(json, status) {

	 	var commentObj = $.parseJSON(json);

		 if(typeof commentObj == 'object') { // success
		 	console.log(commentObj);
		 	
		 	$comment = $.fn.comment(commentObj)
 			$("#comments").prepend($comment); // prepend the comment into the comment div

 			// increment the comments count
 			var commentscount = parseInt($('#commentscount').text()) + 1;
 			$('#commentscount').text(commentscount);

 			$('#emptycmt').remove();
 			$('.reply textarea').val('');
 			$('#subcomment').hide();
		 } else { // fail
		 	console.log(json);
		 }
			$('#subcomment').addClass('disabled');
			$('.commentz form').removeClass('loading');
		},
		error: function(xhr, desc, err) {
			console.log(xhr);
			console.log("Details: " + desc + "\nError:" + err);
		}
	 }); // end ajax call
	});
});

// comment delete
$(function(){
	$('.commentz').on('click', '#del', function(e){
		console.log($(this));
		e.stopPropagation();
		e.preventDefault();
		var $commentDOM = $(this).parents('.comment');
		var $id = $(this).parents('.comment').attr('id');
		var $emptycmt = '<span id=\"emptycmt\">There is nothing here yet, be the first to comment!</span>'
		$.ajax({
			url: './crud/delete.php',
			type: 'post',
			data: {'data':{'action':'delete', 'id': $id}},
			success: function(data, status) {
				var $status = $.parseJSON(data).status;
				if($status == "success") {

						// decrement the comments count
						var commentscount = parseInt($('#commentscount').text()) - 1;
						$('#commentscount').text(commentscount);

						// remove the comment from the DOM
						$commentDOM.remove();

						// if all comments are removed, add the empty comment text
						if (commentscount === 0) {
							$('#comments').append($emptycmt);
						}

					}
				},
				error: function(xhr, desc, err) {
					console.log(xhr);
					console.log("Details: " + desc + "\nError:" + err);
				}
			}); // end ajax call

	});
});

// comment edit
$(function(){
	var $content;
	var $commentDOM;
	var $orgContent;

	// on editing
	$('#comments').on('click', '#edit', function(e){
		e.stopPropagation();
		e.preventDefault();

		var $this = $(this);
		var $newContent = "";
		$commentDOM = $this.closest('.comment');
		var $element = $commentDOM.find('.text h4');
		$content = $element.text();

		var $input = "\
		<div class=\"ui form\">\
		<div class=\"field\">\
		<textarea id=\"content\" rows=\"2\">"+$content+"</textarea><br />\
		<button class=\"ui mini green button\" id=\"save\">Save</button>\
		<button class=\"ui mini button\" id=\"cancel\">Cancel</button>\
		</div>\
		</div>";

		$orgContent = $element.replaceWith($input);
		$commentDOM.find('.actions').hide();

	});

	// on clicking Save
	$('#comments').on('click', '#save', function(e){
		e.stopPropagation();
		var $this = $(this);
		var $commentID = $commentDOM.attr('id');

		var $newContent = $commentDOM.find('textarea#content').val();
		console.log($newContent);

		if (typeof $newContent == 'undefined') return; // if content is not defined
		if ($newContent.trim() == '') return; // if comment is empty

		if ($content == $newContent) { // if the new content is the same as the current content
			$('#cancel').click();
			return;
		}

		// add some sexy events
		$this.closest('.comment').find('.form').addClass('loading');

		$.ajax({
			url: './crud/editComment.php',
			type: 'post',
			data: {'data':{'action':'edit', 'content': $newContent, 'id':$commentID}},
			success: function(data, status) {
				var $data = $.parseJSON(data);
				var $editedDOM = '(edited <span id="editedDate" title="'+$data.edit_date+'">'+moment($data.edit_date).fromNow()+'</span>)';
				if($data.status == "success") {

					if($commentDOM.find('#editedDate').length > 0){
						$commentDOM.find('#editedDate').text(moment($data.edit_date).fromNow())
					} else {
						$commentDOM.find('.metadata').append($editedDOM);
					}

					$commentDOM.find('.form').replaceWith('<h4>'+$newContent+'</h4>');
					$('.comment .actions').show();
				}
			},
			error: function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			}
		}); // end ajax call

	});

	// on clicking Cancel
	$('#comments').on('click', '#cancel', function(e){
		e.stopPropagation();
		var $this = $(this);

		$(this).closest('.comment').find('.form').replaceWith($orgContent);
		$commentDOM.find('.actions').show();
		return null;
	});
});

// parsing and displaying times
$(function(){
	$('.comments').each(function(index, value) {
		$date = $(this).find('#commentDate').text();
		$(this).find('#commentDate').text(moment($date).fromNow());

		$date = $(this).find('#editedDate').text();
		$(this).find('#editedDate').text(moment($date).fromNow());
	});
});

// the comment object
$.fn.comment = function(DataObject){
	var $comment = " \
	<div class=\"ui comments\">\
	<div class=\"comment\" id=\""+DataObject.id+"\">\
	<a class=\"avatar\" href=\"/sha/students/"+DataObject.uid+"/\">\
	<img src=\""+DataObject.path+"\">\
	</a>\
	<div class=\"content\">\
	<a class=\"author\" href=\"/sha/students/"+DataObject.uid+"/\">"+DataObject.name+"</a>\
	<div class=\"metadata\">\
	<span class=\"date\">"+moment(DataObject.created).fromNow()+"</span>\
	</div>\
	<div class=\"text\">\
	<h4>"+DataObject.content+"</h4>\
	</div>\
	<div class=\"actions\">\
	<i class=\"heart red icon\"></i>\
	<a class=\"edit\" id=\"edit\">Edit</a>\
	<a style=\"color:red;\" class=\"delete\" id=\"del\">Delete</a>\
	</div>\
	</div>\
	</div>\
	</div>"
	return $comment;
};

$(function(){
	$('.comment-vote-btn.voted i').hover(
		function() {
    		$(this).toggleClass("empty");
  		}
  	);
});