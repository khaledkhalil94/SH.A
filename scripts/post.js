// post upvote/downvote
$(function(){
	$(document).on('click', '#votebtn', function(e){

		e.preventDefault();

		_this = $(this)
		$postID = $('#post-page').attr('post-id');

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

// post delete
$(function(){

	$(document).on('click', '#post-delete', function(e){
		e.preventDefault();
		
		$('.ui.modal.post.delete').modal('show');

	});

	$('#post-confirmDel').click(function(e){

		e.stopPropagation();

		$postID = $('#post-page').attr('post-id');

		// add loading
		$('.modal.post.delete .segment').addClass('loading');
		
		$.ajax({
			url: '/sha/controllers/_question.php',
			type: 'post',
			dataType : 'json',
			data: {'action':'post_delete', 'id': $postID},

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
								<h3>"+data.err+"</h3>\
								</ul>\
								</div>";
					$('.modal.post.delete .segment').removeClass('loading').addClass('raised padded');
					$('#post-delete').parent().removeClass('active selected');
					$('.modal.post.delete .segment').children().remove();
					$('.modal.post.delete .segment').append($errMsg);

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



$('.ui.dropdown').dropdown();