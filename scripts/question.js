// post vote
$(function(){
	$('.blog-post').on('click', '#votebtn', function(e){

		e.preventDefault();

		_this = $(this)
		$postID = $('.blog-post').attr('id');

		$('#votebtn').addClass("loading");
		if (!_this.hasClass('voted')) { // if not upvoted
			$.ajax({
				url: '/sha/controllers/_question.php',
				type: 'post',
				dataType : 'json',
				data: {'action':'upvote','id': $postID},

				success: function(data, status) {
					if(data.status == true) {
						$(_this).removeClass("loading grey");
						$(_this).addClass("red voted");
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
				url: '/sha/controllers/_question.php',
				type: 'post',
				dataType : 'json',
				data: {'action':'downvote','id': $postID},

				success: function(data, status) {

					if(status == "success") {
						$('#votescount').removeClass("red");
						$(_this).removeClass("red voted loading");
						$(_this).addClass("grey");
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

// post publish
$(function(){

	var $this;

	$('.blog-post').on('click', '#post-publish', function(e){
		e.preventDefault();

		$this = $(this);

		$('.ui.modal.post.publish').modal('show');

	});

	$('#post-confirmPub').click(function(e){

		e.stopPropagation();

		$postID = $('.blog-post').attr('id');

		// add loading
		$('.modal.post.publish .segment').addClass('loading');
		
		$.ajax({
			url: '/sha/controllers/_question.php',
			type: 'post',
			dataType : 'json',
			data: {'action':'publish', 'id': $postID},

			success: function(data, status) {

				if(data.status == true) {
					console.log('published');
					$('.modal.post.publish').modal('hide');
					window.location.reload(false);

				} else { 
					var $errMsg = "\
								<div class=\"ui error message\">\
								<i class=\"close icon\" id=\"post-errmsg-close-icon\"></i>\
								<div class=\"header\">\
								Error!\
								</div>\
								<ul class=\"list\">\
								<h3>"+data.err+"</h3>\
								</ul>\
								</div>";
					$('.modal.post.publish .segment').removeClass('loading').addClass('raised padded');
					$('#post-publish').parent().removeClass('active selected');
					$('.modal.post.publish .segment').children().remove();
					$('.modal.post.publish .segment').append($errMsg);

					$('#post-errmsg-close-icon').click(function(e){
						$('.modal.post.publish').modal('hide');

					});
				}
				},
			error: function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			}
		}); // end ajax call

	});
});

// post unpublish
$(function(){

	var $this;

	$('.blog-post').on('click', '#post-unpublish', function(e){
		e.preventDefault();

		$this = $(this);

		$('.ui.modal.post.unpublish').modal('show');

	});

	$('#post-confirmUnP').click(function(e){

		e.stopPropagation();

		$postID = $('.blog-post').attr('id');

		// add loading
		$('.modal.post.unpublish .segment').addClass('loading');
		
		$.ajax({
			url: '/sha/controllers/_question.php',
			type: 'post',
			dataType : 'json',
			data: {'action':'unPublish', 'id': $postID},

			success: function(data, status) {

				if(data.status == true) {
					console.log('unpublishd');
					$('.modal.post.unpublish').modal('hide');
					window.location.reload(false);

				} else { 
					var $errMsg = "\
								<div class=\"ui error message\">\
								<i class=\"close icon\" id=\"post-errmsg-close-icon\"></i>\
								<div class=\"header\">\
								Error!\
								</div>\
								<ul class=\"list\">\
								<h3>"+data.err+"</h3>\
								</ul>\
								</div>";
					$('.modal.post.unpublish .segment').removeClass('loading').addClass('raised padded');
					$('#post-unpublish').parent().removeClass('active selected');
					$('.modal.post.unpublish .segment').children().remove();
					$('.modal.post.unpublish .segment').append($errMsg);


					$('#post-errmsg-close-icon').click(function(e){
						$('.modal.post.unpublish').modal('hide');

					});
				}
				},
				error: function(xhr, desc, err) {
					console.log(xhr);
					console.log("Details: " + desc + "\nError:" + err);
				}
			}); // end ajax call

	});
});

// post delete
$(function(){

	var $this;

	$('.blog-post').on('click', '#post-delete', function(e){
		e.preventDefault();

		$this = $(this);
		
		$('.ui.modal.post.delete').modal('show');

	});

	$('#post-confirmDel').click(function(e){

		e.stopPropagation();

		$postID = $('.blog-post').attr('id');

		// add loading
		$('.modal.post.delete .segment').addClass('loading');
		
		$.ajax({
			url: '/sha/controllers/_question.php',
			type: 'post',
			dataType : 'json',
			data: {'action':'delete', 'id': $postID},

			success: function(data, status) {
				if(data.status == true) {
					window.location.replace('./')

				} else { 
					var $errMsg = "\
								<div class=\"ui error message\">\
								<i class=\"close icon\" id=\"post-errmsg-close-icon\"></i>\
								<div class=\"header\">\
								Error!\
								</div>\
								<ul class=\"list\">\
								<h3>"+data+"</h3>\
								</ul>\
								</div>";
					$('.modal.post.delete .segment').removeClass('loading').addClass('raised padded');
					$('#post-delete').parent().removeClass('active selected');
					$('.modal.post.delete .segment').children().remove();
					$('.modal.post.delete .segment').append($errMsg);

					console.log(data);

					$('#post-errmsg-close-icon').click(function(e){
						$('.modal.post.delete').modal('hide');

					});
				}
			},
			error: function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			}
		}); // end ajax call

	});
});

// post edit
$(function(){
	var $postOrgContent;
	var $newContent;

	// on editing
	$('.blog-post').on('click', '#post-edit', function(e){
		e.stopPropagation();
	
		$postDOM = $(this).closest('.blog-post');
		$content = $postDOM.find('.ui.container p').text();
		$title = $postDOM.find('.ui.header h3').text();

		var $titleEdit = "\
			<div class=\"ui vertical segment title-form form\">\
			<div class=\"field\">\
			<textarea style=\"resize:none;\" id=\"content\" rows=\"1\">"+$title+"</textarea><br />\
			</div>\
			</div>";
		var $contentEdit = "\
			<div class=\"ui vertical segment content-form form\">\
			<div class=\"field\">\
			<textarea id=\"content\" rows=\"8\">"+$content+"</textarea><br />\
			</div>\
			</div>";
		var $newActions = "\
			<div class=\"actions\">\
			<button class=\"ui green button\" id=\"post-confirm-save\">Save</button>\
			<button class=\"ui button\" id=\"post-cancel\">Cancel</button>\
			</div>";

		$postOrgTitle = $postDOM.find('.header .blog-post-title').replaceWith($titleEdit);
		$postOrgContent = $postDOM.find('.container p').replaceWith($contentEdit);


		$postOrgActions = $postDOM.find('.actions').replaceWith($newActions);

		$('.ui.pointing.dropdown').hide();
	});

	// on clicking Save
	$('.blog-post').on('click', '#post-confirm-save', function(e){
		e.stopPropagation();

		var $PostID = $postDOM.attr('id');

		$newContent = $postDOM.find('.content-form textarea#content').val();
		$newTitle = $postDOM.find('.title-form textarea#content').val();

		if (typeof $newContent == 'undefined') return; // if content is not defined
		if ($newContent.trim() == '') return; // if comment is empty

		if (($content == $newContent) && ($title == $newTitle)) { // if the new content is the same as the current content
			$('#post-cancel').click();
			return;
		}

		if ($content != $newContent) {
			$postDOM.find('.form.content-form').addClass('loading');
		} else {
			$postDOM.find('.form.content-form').replaceWith($postOrgContent);
		}
			
		if ($title != $newTitle) {
			$postDOM.find('.form.title-form').addClass('loading');
		} else {
			$postDOM.find('.form.title-form').replaceWith($postOrgTitle);
		}

		$('#post-edit').parent().removeClass('active selected');
		$('.ui.pointing.dropdown').show();

		$.ajax({
			url: '/sha/controllers/_question.php',
			type: 'post',
			dataType : 'json',
			data: {'action':'edit', 'title': $newTitle, 'content': $newContent, 'id':$PostID},

			success: function(data, status) {
				if(data.status == true) {

					var $editedDOM = '(edited <span id="post-date-ago">A few seconds ago</span>)';

					if($postDOM.find('#post-date-ago').length > 0){
						$postDOM.find('#post-date-ago').text('A few seconds ago');
					} else {
						$postDOM.find('.time').append($editedDOM);
					}

					$postDOM.find('.form.content-form').replaceWith('<p>'+$newContent+'</p>');
					$postDOM.find('.form.title-form').replaceWith('<h3>'+$newTitle+'</h3>');

					$postDOM.find('.actions').replaceWith($postOrgActions);
				}
			},
			error: function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			}
		}); // end ajax call
	});

	// on clicking Cancel
	$('.blog-post').on('click', '#post-cancel', function(e){
		e.stopPropagation();

		$postDOM.find('.content-form.form').replaceWith($postOrgContent);
		$postDOM.find('.title-form.form').replaceWith($postOrgTitle);

		$postDOM.find('.actions').replaceWith($postOrgActions);

		$('#post-edit').parent().removeClass('active selected');
		$('.ui.pointing.dropdown').show();

		return null;
	});
});

// toggle hover class
$(function(){
	$('.comment-vote-btn.voted i').hover(
		function() {
    		$(this).toggleClass("empty");
  		}
  	);
});

$('.message .close')
  .on('click', function() {
    $(this)
      .closest('.message')
      .transition('fade');
  });


