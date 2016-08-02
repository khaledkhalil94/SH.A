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

		console.log($value);
		console.log($name);

		$element = $this.parents('.field').find('.label');
		$vis = $element.hasClass('label');


		$.ajax({
			url: '../settings/api/privacy.php',
			type: 'post',
			data: {'submit': 'submit', [$name] : $value},

			success: function(data, status) {
				json = $.parseJSON(data);
				console.log(json);

				if(json.status == "success"){ // success

					if(!$vis) $this.parents('.field').after($saved);
					
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
$(function (){
	$('.settings-content .user-settings-information .ui.form .update ').click(function(e){

		e.preventDefault();

		var $isValid = $('.ui.form').form('is valid');
		//console.log($isValid);

		var $values = $('.ui.form').form('get values');
		var $this = $(this);

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

		console.log($cvalues);

		var $newValues = {};

		$.each($values, function (index, value) {
			if(value != $cvalues[index]){
				$newValues[index] = value;
			}
		});

		console.log($newValues);

		if ($.isEmptyObject($newValues)) {
			console.log('No fields changed.');
			return null;
		}

		$this.parents('form').addClass('loading');

		if($isValid){
			$.ajax({
				url: '../settings/api/information.php',
				type: 'post',
				data: {'submit': {'values' : $newValues}},

				success: function(data, status) {
					json = $.parseJSON(data);
					console.log(json);

					if(json.status == "success"){ // success

						$this.parents('form').removeClass('loading');

						var $saved = "\
								<div class=\"output\">\
									<div id=\"input-saved\" class=\"ui green label\">\
										<i class=\"check icon\"></i>Saved\
									</div>\
								</div>";

						$.each($newValues, function(i, v) {

							$('form').find('.'+i+' :input').css({"background-color": "#dcffdc"});
							$('.ui.segment.user-settings-information').css({"border-color": '#30ad00'});


							$vis = $('form').find('.'+i).next().hasClass('output');

							if(!$vis) $('form').find('.'+i).after($saved);
							

						});

						setTimeout(function() {
							$.each($newValues, function(i, v) {

								$('form').find('.field').next('.output').fadeOut(function(){
									this.remove();
								});

							});
						}, 2000 );

						return true;
						
					} else { // failure

						
						//if(!$vis) $this.parents('.field').after($error);
						
						$this.parents('form').removeClass('loading');

						var $error = "\
									<div class=\"output\">\
										<div id=\"input-saved\" class=\"ui red label\">\
											<i class=\"remove icon\"></i>Error!\
										</div>\
									</div>";

						$.each($newValues, function(i, v) {
							$('form').find('.'+i+' :input').css({"background-color": "#f3bbbb"});
							

							$vis = $('form').find('.'+i).next().hasClass('output');

							if(!$vis) $('form').find('.'+i).after($error);
							
						});

						setTimeout(function() {
							$.each($newValues, function(i, v) {
								$('form').find('.field').next('.output').fadeOut(function(){
									this.remove();
								});
							});
						}, 3500 );

						return false;
					}
				},
				error: function(xhr, desc, err) {
					console.log(xhr);
					console.log("Details: " + desc + "\nError:" + err);
				}
			}); // end ajax call
		}
	});

	return null;
});

// links update settings
$(function (){
	$('.settings-content .user-settings-links .ui.form .update ').click(function(e){

		e.preventDefault();

		var $isValid = $('.ui.form').form('is valid');
		//console.log($isValid);

		var $values = $('.ui.form').form('get values');
		var $this = $(this);

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

		console.log($cvalues);

		var $newValues = {};

		$.each($values, function (index, value) {
			if(value != $cvalues[index]){
				$newValues[index] = value;
			}
		});

		console.log($newValues);

		if ($.isEmptyObject($newValues)) {
			console.log('No fields changed.');
			return null;
		}

		$this.parents('form').addClass('loading');

		if($isValid){
			$.ajax({
				url: '../settings/api/information.php',
				type: 'post',
				data: {'submit': {'values' : $newValues}},

				success: function(data, status) {
					json = $.parseJSON(data);
					console.log(json);

					if(json.status == "success"){ // success

						$this.parents('form').removeClass('loading');

						var $saved = "\
								<div class=\"output\">\
									<div id=\"input-saved\" class=\"ui green label\">\
										<i class=\"check icon\"></i>Saved\
									</div>\
								</div>";

						$.each($newValues, function(i, v) {

							$('form').find('.'+i+' :input').css({"background-color": "#dcffdc"});
							$('.ui.segment.user-settings-information').css({"border-color": '#30ad00'});


							$vis = $('form').find('.'+i).next().hasClass('output');

							if(!$vis) $('form').find('.'+i).after($saved);
							

						});

						setTimeout(function() {
							$.each($newValues, function(i, v) {

								$('form').find('.field').next('.output').fadeOut(function(){
									this.remove();
								});

							});
						}, 2000 );

						return true;
						
					} else { // failure

						
						//if(!$vis) $this.parents('.field').after($error);
						
						$this.parents('form').removeClass('loading');

						var $error = "\
									<div class=\"output\">\
										<div id=\"input-saved\" class=\"ui red label\">\
											<i class=\"remove icon\"></i>Error!\
										</div>\
									</div>";

						$.each($newValues, function(i, v) {
							$('form').find('.'+i+' :input').css({"background-color": "#f3bbbb"});
							

							$vis = $('form').find('.'+i).next().hasClass('output');

							if(!$vis) $('form').find('.'+i).after($error);

						});

						setTimeout(function() {
							$.each($newValues, function(i, v) {
								$('form').find('.field').next('.output').fadeOut(function(){
									this.remove();
								});
							});
						}, 3500 );

						return false;
					}
				},
				error: function(xhr, desc, err) {
					console.log(xhr);
					console.log("Details: " + desc + "\nError:" + err);
				}
			}); // end ajax call
		}
	});

	return null;
});

$('.special.cards .image').dimmer({
	on: 'hover',
	'opacity' : .4
});


$(document).on('click', '#changePicture', function(){
	$('#myFile').click();
});

$(document).on('click', '#uploadPicture', function(){
	$('#myFile').click();
});

// TODO
// ADD EFFECTS

$('#myFile').on('change', function(e){
	var file;
	file = e.target.files[0];

	var data = new FormData();
	data.append('file', file);

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

					var $viewBtn = "\
										<a class=\"ui icon button\" href=\""+ data.path +"\" data-variation=\"mini\" data-content=\"View Picture\" >\
										<i data-variation=\"mini\" class=\"unhide icon link\"></i>\
										</a>";

					var $changeBtn = "\
										<div id=\"changePicture\" class=\"ui small icon button\" data-content=\"Change Picture\" data-variation=\"mini\">\
										<i class=\"edit icon link\"></i>\
										</div>";

					var $deleteBtn =	"\
										<div id=\"deletePicture\" class=\"ui small icon button\" data-content=\"Delete Picture\" data-variation=\"mini\">\
										<i class=\"trash outline icon link\"></i>";

	  			$('#proflePicture').attr('src', data.path);


	  			$('.profile-picture-actions').children().remove();

	  			$('.profile-picture-actions').append($viewBtn,$changeBtn,$deleteBtn);

	  			$('#viewPicture').attr('href', data.path);
		  },

		  error: function(jqXHR, textStatus, errorThrown){
				// Handle errors here
				console.log('ERRORS: ' + textStatus);
		  }
	 });
});

$(document).on('click', '#deletePicture', function(e){
	
	 $.ajax({
			url: '../settings/api/profilePic.php',
			type: 'post',
			data: {'action' : 'delete'},
			dataType: 'json',
		  success: function(data, textStatus){

				var $upBtn = "\
									<div id=\"uploadPicture\" class=\"ui small icon button\" data-content=\"Upload Picture\" data-variation=\"mini\">\
									<i class=\"cloud upload icon link\"></i>\
									</div>";

		  		if(data.status == 'success'){
		  			$('#proflePicture').attr('src', data.path);

		  			$('.profile-picture-actions').children().remove();
		  			$('.profile-picture-actions').append($upBtn);
		  		}
	  			
		  },
		  error: function(jqXHR, textStatus, errorThrown){
				// Handle errors here
				console.log('ERRORS: ' + textStatus);
		  }
	 });
});

$('.ui.icon.button').popup({
   'position' : 'top right'
  });