$(function(){

	$('.comments').each(function() {
		$date = $(this).find('#commentDate').text();
		$(this).find('#commentDate').text(moment($date).fromNow());

		$date = $(this).find('#editedDate').text();
		$(this).find('#editedDate').text(moment($date).fromNow());
	});

	$('#questions .items').each(function(index, value) {
		$date = $(this).find('#post-date').text();
		$(this).find('#post-date').text(moment($date).fromNow());
	});
});

$('.ui.dropdown').dropdown();

function getUrlVars(){

    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}