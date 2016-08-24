// post vote
$(function(){
	$('.blog-post').on('click', '#votebtn', function(e){

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

		var $id = $postID;
		console.log($id);

		// add loading
		$('.modal.post.publish .segment').addClass('loading');
		
		$.ajax({
			url: './crud/unpublish.php',
			type: 'post',
			data: {'status':{'action':'post-publish', 'id': $id}},
			success: function(data, status) {
				json = $.parseJSON(data);
				var $status = json.status;
				if($status == "success") {
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
								<h3>"+json.msg+"</h3>\
								</ul>\
								</div>";
					$('.modal.post.publish .segment').removeClass('loading').addClass('raised padded');
					$('#post-publish').parent().removeClass('active selected');
					$('.modal.post.publish .segment').children().remove();
					$('.modal.post.publish .segment').append($errMsg);

					console.log(json);

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

		var $id = $postID;
		console.log($id);

		// add loading
		$('.modal.post.unpublish .segment').addClass('loading');
		
		$.ajax({
			url: './crud/unpublish.php',
			type: 'post',
			data: {'status':{'action':'post-unpublish', 'id': $id}},
			success: function(data, status) {
				json = $.parseJSON(data);
				var $status = json.status;
				if($status == "success") {
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
								<h3>"+json.msg+"</h3>\
								</ul>\
								</div>";
					$('.modal.post.unpublish .segment').removeClass('loading').addClass('raised padded');
					$('#post-unpublish').parent().removeClass('active selected');
					$('.modal.post.unpublish .segment').children().remove();
					$('.modal.post.unpublish .segment').append($errMsg);

					console.log(json);

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

		var $id = $this.parents('.blog-post').attr('id');
		console.log($id);

		// add loading
		$('.modal.post.delete .segment').addClass('loading');
		
		$.ajax({
			url: './crud/delete.php',
			type: 'post',
			data: {'delete':{'action':'post-delete', 'id': $id}},
			success: function(data, status) {
				json = $.parseJSON(data);
				var $status = json.status;
				if($status == "success") {
					console.log('deleted');
					window.location.replace('./')

					} else { 
						var $errMsg = "\
									<div class=\"ui error message\">\
									<i class=\"close icon\" id=\"post-errmsg-close-icon\"></i>\
									<div class=\"header\">\
									Error!\
									</div>\
									<ul class=\"list\">\
									<h3>"+json.msg+"</h3>\
									</ul>\
									</div>";
						$('.modal.post.delete .segment').removeClass('loading').addClass('raised padded');
						$('#post-delete').parent().removeClass('active selected');
						$('.modal.post.delete .segment').children().remove();
						$('.modal.post.delete .segment').append($errMsg);

						console.log(json);

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

		$this = $(this);
		
		$postDOM = $this.closest('.blog-post');
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

		$postOrgTitle = $postDOM.find('.header .blog-post-title').replaceWith($titleEdit);
		$postOrgContent = $postDOM.find('.container p').replaceWith($contentEdit);

		var $newActions = "\
			<div class=\"actions\">\
			<button class=\"ui green button\" id=\"post-confirm-save\">Save</button>\
			<button class=\"ui button\" id=\"post-cancel\">Cancel</button>\
			</div>";

		$postOrgActions = $postDOM.find('.actions').replaceWith($newActions);

		$('.ui.pointing.dropdown').hide();
	});

	// on clicking Save
	$('.blog-post').on('click', '#post-confirm-save', function(e){
		e.stopPropagation();

		var $PostID = $postDOM.attr('id');

		$newContent = $postDOM.find('.content-form textarea#content').val();
		$newTitle = $postDOM.find('.title-form textarea#content').val();
		console.log($newContent);

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
			url: './crud/editComment.php',
			type: 'post',
			data: {'data':{'action':'post-edit', 'title': $newTitle, 'content': $newContent, 'id':$PostID, 'user_id':$userID}},

			success: function(data, status) {
				var json = $.parseJSON(data);
				console.log(json)
				if(json.status == "success") {

					var $editedDOM = '(edited <span id="post-date-ago" title="'+json.edit_date+'">'+moment(json.edit_date).fromNow()+'</span>)';

					if($postDOM.find('#post-date-ago').length > 0){
						$postDOM.find('#post-date-ago').text(moment(json.edit_date).fromNow())
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
		console.log('can');
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

// post/comment report
// BUGGED >> TO BE FIXED SOON^valvetime
// $(function(){

// 	var $element;
// 	var $postID;
// 	var $content;
// 	var $orgDOM;
// 	var $reportConfirm;
// 	var $errMsg;

// 	// activating the modal on clicking report
// 	$('.container.section').on('click', '#post_report', function(e){

// 		$this = $(this);

// 		// check if it's a blog post or a comment
// 		var $Post = ($this.parents('.blog-post').length) ? true : false;

// 		if($Post){ 

// 			$element = $this.closest('.blog-post');
// 			$postID = $element.attr('id');
// 			console.log($postID);

// 		} else { 

// 			$element = $this.closest('.comment');
// 			$postID = $element.attr('id');
// 			console.log($postID);

// 		}
		
// 		$('.ui.modal.report').modal("setting", {
// 			onHidden: function () {
// 				$('#report-cancel').click();
// 				if (typeof($orgDOM) !== 'undefined') {
// 					$('.modal.report .message').replaceWith($orgDOM);
// 				}
// 				$('.modal.report .actions').append($reportConfirm);
// 			}
// 		}).modal("show");
		

// 		if($Post){ // if it's a blog post

// 			$('.ui.message').remove();

// 		} else { // else it's a comment

// 			$content = $element.find($('h4')).text();
// 			$('.description p').text($content);
// 			console.log($content);

// 		}


// 	});

// 	// toggling the checkbox
// 	$('.modal.report').on('click','.checkbox', function(){ 
// 		$('.checkbox').checkbox({
// 			onChange: function() {
// 				$('#modalForm').toggle(450);
// 			}
// 		});
// 	});


// 	// submitting the report
// 	$('.modal.report').on('click', '#report-confirm', function(e){

// 		var $newContent = $('#modalForm textarea').val();

// 		// ADD class="ui segment loading" instead of the loader
// 		$loader = 
// 				"<div class=\"ui container segment\" style=\"margin:40px 0px;\">\
// 				<div class=\"ui loading form\" id=\"modalForm\">\
// 				<div class=\"field\">\
// 				<textarea rows=\"6\"></textarea>\
// 				</div>\
// 				</div>\
// 				</div>";
// 		$('.checkbox').checkbox('uncheck');
// 		$('#modalForm textarea').val('');
// 		console.log($('.description p'));
// 		$orgDOM = $('.modal.report .content').replaceWith($loader);

// 		$.ajax({
// 			url: './crud/report.php',
// 			type: 'post',
// 			data: {'report':
// 				 	{
// 				 		'action':'report-comment',
// 				 		'content':$newContent,
// 				 		'post_id':$postID,
// 				 		'uid':$userID
// 				 	}
// 				},
// 			success: function(data, status) {

// 				json = $.parseJSON(data);

					
// 				if (json.status == "success") { // report success
// 					console.log("raported");
// 					$sucMsg = "\
// 								<div class=\"ui success message\" style=\"width: 95%; margin: 25px auto;\">\
// 								<div class=\"header\">\
// 								Success!\
// 								</div>\
// 								<ul class=\"list\">\
// 								<li>Comment has been reported, thanks for you submission.</li>\
// 								</ul>\
// 								</div>\
// 								</div>";

// 					$('.modal.report .container').replaceWith($sucMsg);

// 					$reportConfirm = $('#report-confirm');
// 					$reportConfirm.remove();

// 				} else if(json.errKey == 1062) { // duplicate key >> already reported
					
// 						$errMsg = "\
// 								<div class=\"ui error message\" style=\"width: 95%; margin: 25px auto;\">\
// 								<div class=\"header\">\
// 								Error!\
// 								</div>\
// 								<ul class=\"list\">\
// 								<li>You have already reported this comment.</li>\
// 								</ul>\
// 								</div>\
// 								</div>";
// 						$cancelBtn = "\
// 										<div class=\"actions\" style=\"text-align:right;\">\
// 										<div class=\"ui white deny button\" id=\"report-cancel\">Close\
// 										</div>\
// 										</div>";

// 						$('.modal.report .container').replaceWith($errMsg);

// 						$reportConfirm = $('#report-confirm');
// 						$reportConfirm.remove();

// 				} else {
// 					$errMsg = "\
// 								<div class=\"ui error message\" style=\"width: 95%; margin: 25px auto;\">\
// 								<div class=\"header\">\
// 								Error!\
// 								</div>\
// 								<ul class=\"list\">\
// 								<li>"+json.errMsg+".</li>\
// 								</ul>\
// 								</div>\
// 								</div>";

// 					$('.modal.report .container').replaceWith($errMsg);
// 					$reportConfirm = $('#report-confirm');
// 					$reportConfirm.remove();
// 					console.log(json);
// 				}
// 			},
// 			error: function(xhr, desc, err) {
// 				console.log(xhr);
// 				console.log("Details: " + desc + "\nError:" + err);
// 			}
// 		}); // end ajax call
			
// 		//$('#report-cancel').click();
// 	});

// 	// cancelling
// 	$('.modal.report').on('click', '#report-cancel', function(e){
// 		e.stopPropagation();
// 		console.log('cancelling');
// 		$newContent = '';
// 		$('#modalForm textarea').val('');
// 		$('.checkbox').checkbox('uncheck');
	
// 	});
// });


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

//parsing and displaying times
$(function(){

	$('#post-date').text(moment($('#post-date').text()).fromNow());
	$('#post-date-ago').text(moment($('#post-date-ago').text()).fromNow());


	// $('.comments').each(function(index, value) {
	// 	$date = $(this).find('#commentDate').text();
	// 	$(this).find('#commentDate').text(moment($date).fromNow());

	// 	$date = $(this).find('#editedDate').text();
	// 	$(this).find('#editedDate').text(moment($date).fromNow());
	// });
});

//$('.ui.dropdown').dropdown();