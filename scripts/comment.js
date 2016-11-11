// comment upvote/downvote
$(function(){
	$('#comments').on('click', '.comment-vote-btn', function(e){
		e.preventDefault();
		e.stopPropagation();

		var $this = $(this);
		var $comment = $this.closest('.comment');
		var $id = $comment.attr('comment-id');

		if (!$this.hasClass('voted')) { // if not upvoted
			$.ajax({
				url: '/controllers/_comment.php',
				type: 'post',
				dataType: 'json',
				data: {'action': 'upvote', 'comment_id' : $id},

				// vote success
				success: function(data, status) {
					if(data.status == true) {
						$comment.find($('.comment-vote-btn')).addClass("voted");
						$comment.find($('.comment-vote-btn i')).addClass("yellow");

						var $votescount = parseInt($comment.find($('.comment-votes-count')).text());
						$votescount = isNaN($votescount) ? '+1' : '+'+($votescount + 1);

						$comment.find($('.comment-votes-count')).text($votescount);
					}
				},
				error: function(xhr, desc, err) {
					console.log(xhr);
					console.log("Details: " + desc + "\nError:" + err);
				}
			}); // end ajax call
		} else { // downVote
			$.ajax({
				url: '/controllers/_comment.php',
				type: 'post',
				dataType: 'json',
				data: {'action': 'downvote', 'comment_id' : $id},
				success: function(data, status) {
					if(data.status == true) {
						$comment.find($('.comment-vote-btn')).removeClass("voted");
						$comment.find($('.comment-vote-btn i')).removeClass("yellow");

						var $votescount = parseInt($comment.find($('.comment-votes-count')).text());

						$votescount = ($votescount == 1) ? '' : '+'+($votescount - 1);

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

// new comment
$(function(){
	$("#comment-submit-textarea").focusin(function(){
		$('#subcomment').show();
	});

	// undisable the submit button when there're content
	$("#comment-submit-textarea").keyup(function(){
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
		var $content = $('textarea').val();
		token = $("input[name='comment_token']").val();

		$id = $('.blog-post').attr('id') || $('#post-page').attr('post-id');

	 	$('.commentz form').addClass("loading");

		$.ajax({
		 	url: '/controllers/_comment.php',
		 	type: 'post',
		 	dataType: 'json',
		 	data: {
					'action': 'new_comment', 
					'content':$content,
					'post_id':$id,
					'token':token
				 	},

			success: function(data, status) {

				 if(data.status == true) { // success
				 	
				 	$.get('/controllers/_view.php', {'action' : 'renderComment', 'id' : data.id}, function(res){

			 			$("#comments").prepend(res); // prepend the comment into the comment div
						$('.ui.dropdown').dropdown();

			 			// increment the comments count
			 			var commentscount = parseInt($('#commentscount').text()) + 1;
			 			$('#commentscount').text(commentscount);

						$('#subcomment').addClass('disabled');
						$('.commentz form').removeClass('loading');

			 			$('#emptycmt').remove();
			 			$('.reply textarea').val('');
			 			$('#subcomment').hide();
					}, 'html');
				 	return;

				 } else { // fail

				 	$('.commentz form').removeClass('loading');
				 	console.log(data);

				 }

					$('#subcomment').addClass('disabled');
					$('.commentz form').removeClass('loading');
			},
			error: function(xhr, desc, err) {
				$('.commentz form').removeClass('loading');
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			}
		}); // end ajax call
	});
});

// post/comment report
$(function(){

	var $element;
	var $postID;
	var $content;
	var $orgDOM;
	var $reportConfirm;
	var $errMsg;
	var _this;
	var app = false;
	// activating the modal on clicking report
	$(document).on('click', '#post_report', function(e){

		_this = $(this);

		// check if it's a blog post or a comment
		var $Post = (_this.parents('.blog-post').length) ? true : false;

		if($Post){ 
			$type = 'post';
			$element = _this.closest('.blog-post');
			$postID = $element.attr('id');
		} else { 
			$type = 'comment';
			$element = _this.closest('.comment');
			$postID = $element.attr('comment-id');
		}
		
		$('.ui.modal.report').modal("setting", {
			onHidden: function () {
				$('#report-cancel').click();

				if (typeof($orgDOM) !== 'undefined' && !appd) {
					$('.modal.report .message').replaceWith($orgDOM);
					$('.modal.report .actions').append($reportConfirm);
					appd = true;
				}
			}
		}).modal("show");

		if($Post){ // if it's a blog post

			$('.ui.message').remove();
		} else { // else it's a comment

			$content = $element.find($('h4')).text();
			$('.description p').text($content);
		}
	});
	// toggling the checkbox
	$('.checkbox').checkbox({
		onChange: function() {
			$('#modalForm').toggle(400);
		}
	});

	// submitting the report
	$(document).on('click', '#report-confirm', function(e){

		var $newContent = $('#modalForm textarea').val();
		appd = false;
		// ADD class="ui segment loading" instead of the loader
		$loader = 
				"<div class=\"ui container segment\" style=\"margin:40px 0px;\">\
				<div class=\"ui loading form\" id=\"modalForm\">\
				<div class=\"field\">\
				<textarea rows=\"6\"></textarea>\
				</div>\
				</div>\
				</div>";
		$('.checkbox').checkbox('uncheck');
		$('#modalForm textarea').val('');
		$orgDOM = $('.modal.report .content').replaceWith($loader);

		$.ajax({
			url: '/controllers/_comment.php',
			type: 'post',
			dataType : 'json',
			data: {
					'action':'report',
					'type' : $type,
					'content':$newContent,
					'post_id':$postID
				},
			success: function(data, status) {
				console.log(data);
				if (data.status == true) { // report success
					$sucMsg = "\
								<div class=\"ui success message\" style=\"width: 95%; margin: 25px auto;\">\
								<div class=\"header\">\
								Success!\
								</div>\
								<ul class=\"list\">\
								<li>Comment has been reported, thanks for you submission.</li>\
								</ul>\
								</div>\
								</div>";

					$('.modal.report .container').replaceWith($sucMsg);

					$reportConfirm = $('#report-confirm');
					$reportConfirm.remove();

				} else if(data.err == 1062) { // duplicate key >> already reported
					
					$errMsg = "\
							<div class=\"ui error message\" style=\"width: 95%; margin: 25px auto;\">\
							<div class=\"header\">\
							Error!\
							</div>\
							<ul class=\"list\">\
							<li>You have already reported this comment.</li>\
							</ul>\
							</div>\
							</div>";
					$cancelBtn = "\
							<div class=\"actions\" style=\"text-align:right;\">\
							<div class=\"ui white deny button\" id=\"report-cancel\">Close\
							</div>\
							</div>";

					$('.modal.report .container').replaceWith($errMsg);

					$reportConfirm = $('#report-confirm');
					$reportConfirm.remove();

				} else {
					$errMsg = "\
							<div class=\"ui error message\" style=\"width: 95%; margin: 25px auto;\">\
							<div class=\"header\">\
							Error!\
							</div>\
							<ul class=\"list\">\
							<li>"+data.err+".</li>\
							</ul>\
							</div>\
							</div>";

					$('.modal.report .container').replaceWith($errMsg);
					$reportConfirm = $('#report-confirm');
					$reportConfirm.remove();
				}
			},
			error: function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			}
		}); // end ajax call

	});

	// cancelling
	$('.modal.report').on('click', '#report-cancel', function(e){
		e.stopPropagation();
		console.log('cancelling');
		$newContent = '';
		$('#modalForm textarea').val('');
		$('.checkbox').checkbox('uncheck');
		$('#modalForm').hide();
		
	});
});

// comment delete
$(function(){

	var $this;

	$(document).on('click', '#del', function(e){
		e.preventDefault();

		$this = $(this);
		var $content = $this.closest('.comment').find($('h4')).text();

		$('.description p').text($content);
		$('.ui.modal.comment.delete').modal('show');

	});

	$('#comment-confirmDel').click(function(e){

		e.stopPropagation();
		e.preventDefault();

		var $commentDOM = $this.parents('.comment');
		var $id = $this.parents('.comment').attr('comment-id');
		var $emptycmt = '<span id=\"emptycmt\">There is nothing here yet, be the first to comment!</span>'

		$.ajax({
			url: '/controllers/_comment.php',
			dataType: 'json',
			type: 'post',
			data: {'action':'delete', 'id': $id},
			success: function(data, status) {
				if(data.status == true) {

					// decrement the comments count
					var commentscount = parseInt($('#commentscount').text()) - 1;
					$('#commentscount').text(commentscount);

					// remove the comment from the DOM
					$commentDOM.parent().remove();

					// if all comments are removed, add the empty comment text
					if (commentscount === 0) {
						$('#comments').append($emptycmt);
					}

					$('.ui.small.modal').modal('hide');

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
	var $newContent = "";
	var action;
	var points;
	var working = false;

	// on editing
	$(document).on('click', '#edit', function(e){
		e.stopPropagation();
		e.preventDefault();

		if(working) $('#cancel').click();
		working = true;

		_this = $(this);

		$commentDOM = _this.closest('.comment');
		$element = $commentDOM.find('.text h4');
		$content = $element.text();
		action = $commentDOM.find('#comment-actions');
		points = $commentDOM.find('.comment-points');

		var $input = "\
					<div class=\"ui vertical segment content-form form\">\
					<div class=\"field\" style=\"clear: none;\">\
					<textarea id=\"content\" rows=\"2\">"+$content+"</textarea><br />\
					</div>\
					<button class=\"ui mini green button\" id=\"save\">Save</button>\
					<button class=\"ui mini button\" id=\"cancel\">Cancel</button>\
					</div>";

		$orgContent = $element.replaceWith($input);
		_form = _this.closest('.comment').find('.form');

		action.hide();
		points.hide();
	});

	// on clicking Save
	$('#comments').on('click', '#save', function(e){
		e.stopPropagation();

		var commentID = $commentDOM.attr('comment-id');

		$newContent = $commentDOM.find('textarea#content').val();

		if (typeof $newContent == 'undefined') return; // if content is not defined
		if ($newContent.trim() == '') { // if comment is empty
			$commentDOM.find('#del').click();
			return;
		}

		if ($content == $newContent) { // if the new content is the same as the current content
			$('#cancel').click();
			return;
		}

		_form.addClass('loading');

		$.ajax({
			url: '/controllers/_comment.php',
			type: 'post',
			dataType : 'json',
			data: {'action':'edit', 'content': $newContent, 'id':commentID},
			success: function(data, status) {

				if(data.status == true) {
					var $editedDOM = '(edited <span id="editedDate">A few seconds ago</span>)';

					if($commentDOM.find('#editedDate').length > 0){
						$postDOM.find('#editedDate').text('A few seconds ago');
					} else {
						$commentDOM.find('.metadata').append($editedDOM);
					}

					_form.replaceWith('<h4>'+$newContent+'</h4>');

					$commentDOM.find('#comment-actions, .comment-points').show();
				}

				working = false;
			},
			error: function(xhr, desc, err) {
				$('#cancel').click();
				working = false;
			}
		}); // end ajax call

	});

	// on clicking Cancel
	$('#comments').on('click', '#cancel', function(e){
		e.stopPropagation();

		_form.replaceWith($orgContent);

		action.show();
		points.show();

		//$commentDOM.find($('#comment-edit-controllers')).remove();
		working = false;
		return null;
	});
});

// comment hide
$(function(){
	$(document).on('click', '#comment_hide', function(){
		$(this).parents('.minimal.comments').fadeOut(200);
	});
});
