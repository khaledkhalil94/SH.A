//parsing and displaying datetimes
$(function(){
	$('.datetime').each(function() {
		var _t = $(this);
		var d1 = moment.tz(_t.text(), "America/New_York");
		_t.text(d1.fromNow());
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

function errMsg(msg){
	var $errMsg = "\
		<div id=\"profile_err_msg\" class=\"ui negative message\" style='text-align:center;'>\
		<i class=\"close icon\"></i>\
		<div class=\"header\">\
		Error!\
		</div>\
		<p>"+ msg +"\</p>\
		</div>";
	return $errMsg;
}

function sucMsg(msg){
	var $sucMsg = "\
		<div id=\"profile_err_msg\" class=\"ui success message\" style='text-align:center;'>\
		<i class=\"close icon\"></i>\
		<div class=\"header\">\
		Success!\
		</div>\
		<p>"+ msg +"\</p>\
		</div>";
	return $sucMsg;
}

$(function(){
	var uid;
	_popup = $('.user-title');

	_popup.hover(function(){
		uid = $(this).attr('user-id');
	});

	var popupLoading = '<i class="spinner loading icon green"></i> wait...';

	_popup.popup({
	    on: 'hover',
	    exclusive: true,
	    hoverable: true,
	    position: 'top left',
	    html: popupLoading,
	    variation: 'wide',
	    transition: 'vertical flip',
	    onShow: function (el) { // load data (it could be called in an external function.)
	        var popup = this;
	        $.ajax({
	            url: '/controllers/_profile.php',
	            data: {'action' : 'profile_card', 'id' : uid},
	            type: 'post'
	        }).done(function(result) {
	            popup.html(result);
	        }).fail(function() {
	            popup.html('error');
	        });
	    }
	});
});

$(document).on('click', '#user_flw', function(){
	_this = $(this);
	id = _this.attr('user-id');
	_this.addClass('loading');
	$.ajax({
			url: '/controllers/_profile.php',
			type: 'post',
			data: {'action' : 'follow', 'id' : id},
			dataType: 'json',
		  success: function(data, textStatus){
		  	if(data.status === true){

		  		_this.removeClass('loading');
		  		_this.removeClass('green');
		  		_this.addClass('blue');
		  		_this.addClass('following');
		  		_this.text('Following');
		  		_this.attr('id', 'user_unflw');

		  		// TODO: add hover effect
		  	} else {
		  		_this.removeClass('loading');
		  		error = data.err;
		  		$('.profile-body').prepend(errMsg(error));
		  	}
		  },
		  error: function(jqXHR, textStatus, errorThrown){
			console.log('ERRORS: ' + textStatus);
		  }
	 });
});

$(document).on('click', '#user_unflw', function(){
	_this = $(this);
	id = _this.attr('user-id');
	_this.addClass('loading');

	$.ajax({
			url: '/controllers/_profile.php',
			type: 'post',
			data: {'action' : 'unfollow', 'id' : id},
			dataType: 'json',
		  success: function(data, textStatus){
		  	if(data.status === true){
		  		_this.removeClass('loading');
		  		_this.removeClass('blue');
		  		_this.addClass('green');
		  		_this.removeClass('following');
		  		_this.text('Follow');
		  		_this.attr('id', 'user_flw');
		  	} else {
		  		_this.removeClass('loading');
		  		error = data.err;
		  		$('.profile-body').prepend(errMsg('unknown error!'));
		  	}
		  },
		  error: function(jqXHR, textStatus, errorThrown){
			console.log('ERRORS: ' + textStatus);
		  }
	 });
});

$(document).on('mouseover', '#user_unflw', function(){
	$(this).text('unFollow');
	$(this).removeClass('blue');
	$(this).addClass('red');
});

$(document).on('mouseleave', '#user_unflw', function(){
	$(this).text('Following');
	$(this).removeClass('red');
	$(this).addClass('blue');
});

$(document).on('click', '.close.icon', function() {
    $(this)
      .closest('.message')
      .transition('fade')
    ;
  });