$(function(){

	// $('#post-date').text(moment($('#post-date').text()).fromNow());
	// $('#post-date-ago').text(moment($('#post-date-ago').text()).fromNow());


	$('.comments').each(function(index, value) {
		$date = $(this).find('#commentDate').text();
		$(this).find('#commentDate').text(moment($date).fromNow());

		$date = $(this).find('#editedDate').text();
		$(this).find('#editedDate').text(moment($date).fromNow());
	});

	$('#questions .items').each(function(index, value) {
		$date = $(this).find('#post-date').text();
		console.log(index + moment($date).fromNow());
		$(this).find('#post-date').text(moment($date).fromNow());
	});
});
