$( "#extrainfo-collapse" ).click(function() {
	$('#user-extrainfo-list').toggle('fast', function(){
		$('#btn-extrainfo-angle-up').toggleClass('up').toggleClass('down');
	});
});

$( "#links-collapse" ).click(function() {
	$('#user-links-list').toggle('fast', function(){
		$('#btn-angle-up').toggleClass('up').toggleClass('down');
	});
});

// api settings for tabs
$('.user-profile .tabular.menu .item').tab({
	alwaysRefresh: true,
	ignoreFirstLoad:false,
	cache:false,
	apiSettings: {
		//url: '../tabs/{$tab}.php'
		url: '../tabs/feed.php'
	}
});

//$('.user-profile .tabular.menu .active').click();

// time parsing
$('#user-joined-date').text('Joined ' + moment($('#user-joined-date').text()).fromNow());
$('#post-date-ago').text(moment($('#post-date-ago').text()).fromNow());

$('.ui.dropdown').dropdown();

// privacy update settings
$(function(){

	var $saved = "\
					<div class=\"output\">\
						<div id=\"input-saved\" class=\"ui green label\">\
							<i class=\"check icon\"></i>Saved\
						</div>\
					</div>";

	$('.settings-content .user-settings-privacy select').on('change', function(e){

		var $value = this.value;
		var $name = this.name;
		var $this = $(this);

		$output = $this.parents('.field').next().attr('class');

		$.ajax({
			url: '/sha/controllers/_account.php',
			type: 'post',
			dataType : 'json',
			data: {'action': 'privacy_update', [$name] : $value},

			success: function(data, status) {
				console.log(data);

				if(data == "1"){ // success

					if($output != 'output') $this.parents('.field').after($saved);
					
					setTimeout(function() {
						$this.parents('.field').next('.output').fadeOut(function(){
							this.remove();
						});
					}, 1300);
					
				} else { // failure

					var $error = "\
								<div class=\"output\">\
									<div id=\"input-saved\" class=\"ui red label\">\
										<i class=\"remove icon\"></i>Error!\
									</div>\
								</div>";

					if(!$vis) $this.parents('.field').after($error);
					
					setTimeout(function() {
						$this.parents('.field').next('.output').fadeOut(function(){
							this.remove();
						});
					}, 4300);
				}
			},
			error: function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			}
		}); // end ajax call
		
	});
});

// information update settings

$('.settings-content .user-settings-information, .user-settings-links, .user-settings form ').submit(function(e){

	e.preventDefault();
	event.stopImmediatePropagation();

	// get url querystring
	var vars = [], hash;
	var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	for(var i = 0; i < hashes.length; i++)
	{
		hash = hashes[i].split('=');
		vars.push(hash[0]);
		vars[hash[0]] = hash[1];
	}

	action = (vars.st == 'us') ? 'update_settings' : 'update_info';


	var $isValid = $('.ui.form').form('is valid');
	//console.log($isValid);

	var $values = $('.ui.form').form('get values');
	var $this = $(this);
	var _form = $this;


	var $inputs = $('form .field :input');


	var $cvalues = {};
	$inputs.each(function(i, v) {
		if(v.name == 'gender') {
			var $def = ($('option[selected]').val());
			$cvalues[v.name] = $def;
			return;
		}
		$cvalues[this.name] = $(this)[0].defaultValue;
	});

	//console.log($cvalues);

	var $newValues = {};

	$.each($values, function (index, value) {
		if(value != $cvalues[index]){
			$newValues[index] = value;
		}
	});

	//$s_Values = $.extend($cvalues, $newValues);
	console.log($newValues);
	//console.log($s_Values);

	if ($.isEmptyObject($newValues)) {
		console.log('No fields changed.');
		return null;
	}


	if($isValid){
		_form.find('.output').remove();
		_form.addClass('loading');

		$.ajax({
			url: '/sha/controllers/_account.php',
			type: 'post',
			dataType: 'json',
			data: {'action': action, 'values' : $newValues},

			success: function(data, status) {

				//console.log(data);

				if(data == "1"){ // success

					_form.removeClass('loading');

					var $saved = "\
							<div class=\"output error\">\
								<div id=\"input-saved\" class=\"ui green label\">\
									<i class=\"check icon\"></i>Saved\
								</div>\
							</div>";

					$.each($newValues, function(i, v) {

						if(i == 'old_password') return;

						_form.find('.'+i+' :input').css({"background-color": "#dcffdc"});
						_form.parents('.ui.segment').css({"border-color": '#30ad00'});


						$vis = _form.find('.'+i).next().hasClass('output');

						if(!$vis) _form.find('.'+i).after($saved);
						

					});
					console.log($newValues);

					setTimeout(function() {
						$.each($newValues, function(i, v) {
							_form.find('.field').next('.output').fadeOut(function(){
								this.remove();
							});

						});
					}, 2000 );

					//$newValues = '';
					return true;
					
				} else { // failure

					_form.removeClass('loading');

					function $error(v){
						$err = "<div class=\"output\">\
							<div id=\"input-saved\" class=\"ui pointing red label\">\
								"+v+"</div>\
							</div>";
						return $err;
					}

					$.each(data, function(i, v) {

					console.log(i);
						_form.find('.'+i+' :input').css({"background-color": "#f3bbbb"});
						

						$vis = _form.find('.'+i).next().hasClass('output');

						if(!$vis) _form.find('.'+i).after($error(v));
						
					});

					setTimeout(function() {
						$.each($newValues, function(i, v) {
							_form.find('.field').next('.output').fadeOut(function(){
								this.remove();
							});
						});
					}, 3500 );



					return false;
				}
			},
			error: function(xhr, desc, err) {
				_form.removeClass('loading');
				console.log(err);
				console.log(desc);
			}
		}); // end ajax call
	}
	return null;
});


// delete
$(function(){
	$('#acc_del').click(function(){
		$('.ui.delete.segment').load('/sha/controllers/inc/account-delete.php');
	})
})

$('.special.cards .image').dimmer({
	on: 'hover',
	'opacity' : .3
});


// firing the file upload input
$(document).on('click', '#changePicture, #uploadPicture', function(){

	$('#profile_err_msg').remove();
	$('#myFile').click();
});

// upload or change
$('#myFile').on('change', function(e){
	var file;
	file = e.target.files[0];

	var data = new FormData();
	data.append('file', file);


	$('.special.cards .image').dimmer('add content','<div id="temp_loader" class="ui loader"></div>');
	$('.special.cards .image').dimmer({
		'closable' : false
	});
	
	$('#pp_actions').toggle();

	 $.ajax({
			url: '../settings/api/profilePic.php',
			type: 'post',
			data: data,
			dataType: 'json',
			processData: false,
			contentType: false,
			cache: false,
		  success: function(data, textStatus){
				console.log(data);

				if(data.status == "success"){

					unDim();

					var $viewBtn = "\
						<div id=\"viewPicture\" class=\"ui icon button\" data-variation=\"mini\" data-content=\"View Picture\" >\
						<i data-variation=\"mini\" class=\"unhide icon link\"></i>\
						</div>";

					var $changeBtn = "\
						<div id=\"changePicture\" class=\"ui small icon button\" data-content=\"Change Picture\" data-variation=\"mini\">\
						<i class=\"edit icon link\"></i>\
						</div>";

					var $deleteBtn = "\
						<div id=\"deletePicture\" class=\"ui small icon button\" data-content=\"Delete Picture\" data-variation=\"mini\">\
						<i class=\"trash outline icon link\"></i>";

					$('#proflePicture').attr('src', data.path);

					$('.profile-picture-actions').children().remove();

					$('.profile-picture-actions').append($viewBtn,$changeBtn,$deleteBtn);

					$('#viewPicture').attr('href', data.path);

				} else {

					unDim();

					$('#profile_err_msg').remove();
					$('.profile-body').prepend(errMsg(data.errMsg));

				}
		  },

		  error: function(jqXHR, textStatus, errorThrown){

				unDim();

				$('#profile_err_msg').remove();
				$('.profile-body').prepend(errMsg(textStatus));

				console.log('ERRORS: ' + textStatus);
		  }
	 });
});

// delete
$(document).on('click', '#deletePicture', function(e){

	$('.special.cards .image').dimmer('add content','<div id="temp_loader" class="ui loader"></div>');
	$('.special.cards .image').dimmer({
		'closable' : false
	});
	$('#pp_actions').toggle();


	$.ajax({
			url: '../settings/api/profilePic.php',
			type: 'post',
			data: {'action' : 'delete'},
			dataType: 'json',
		  success: function(data, textStatus){

				if(data.status == 'success'){

					unDim();

					var $upBtn = "\
						<div id=\"uploadPicture\" class=\"ui small icon button\" data-content=\"Upload Picture\" data-variation=\"mini\">\
						<i class=\"cloud upload icon link\"></i>\
						</div>";

					if(data.status == 'success'){
						$('#proflePicture').attr('src', data.path);

						$('.profile-picture-actions').children().remove();
						$('.profile-picture-actions').append($upBtn);
					}
				} else {

					unDim();

					$('#profile_err_msg').remove();
					$('.profile-body').prepend(errMsg(data.errMsg));

				}
				
		  },
		  error: function(jqXHR, textStatus, errorThrown){

				unDim();

				$('#profile_err_msg').remove();
				$('.profile-body').prepend(errMsg(textStatus));

				console.log('ERRORS: ' + textStatus);
		  }
	});
});

function errMsg(msg){
	var $errMsg = "\
		<div id=\"profile_err_msg\" class=\"ui negative message\">\
		<i class=\"close icon\"></i>\
		<div class=\"header\">\
		Error uploading picture\
		</div>\
		<p>"+ msg +"\</p>\
		</div>";

	return $errMsg;
}

function unDim(){

	$('#temp_loader').remove();
	$('.special.cards .image').dimmer('toggle');
	$('#pp_actions').toggle();

	$('.special.cards .image').dimmer({
		'closable' : true,
		'on' : 'hover'
	});
}

$(document).on('click', '.message .close', function() {
	$(this).closest('.message').transition('fade');
});

$(document).on('click', '#viewPicture', function(e){
	e.preventDefault();
	$('.page.dimmer:first').dimmer('toggle');

});

$('#dimmer-close').click(function(){
	console.log("??");
	$('.page.dimmer:first').dimmer('hide');

});

$('.ui.icon.button').popup({
	'position' : 'top right'
});

$('.page.dimmer:first').dimmer({
	onShow : function(){
	 $.ajax({
			url: '../settings/api/profilePic.php',
			type: 'get',
			data: {'action' : 'get_pic_info', 'id' : userID},
			dataType: 'json',
		  success: function(data, textStatus){
				if(typeof(data) == 'object'){
					$('#pic_details_name').html('Name: '+data.name+'.'+data.extension);
					$('#pic_details_size').html('Size: '+data.size);
					$('#pic_details_dim').html('Dimensions: '+data.width+' x '+data.height);
					$('#pic_details_link').attr('href', data.path);
					$('#pic_details_link').attr('download', data.name+'.'+data.extension);
					$('#pic_details_pp').attr('src', data.path);
				}

		  },

		  error: function(jqXHR, textStatus, errorThrown){

				unDim();

				$('#profile_err_msg').remove();
				$('.profile-body').prepend(errMsg(textStatus));

				console.log('ERRORS: ' + textStatus);
		  }
	 });
	}
});