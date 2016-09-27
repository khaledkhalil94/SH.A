$(function(){

	var _this;
	var cp=1;

	vars = getUrlVars();
	query = vars.section ? vars.section : null;

	$('.ui.pagination.menu a').click(function(){
		
		_this = $(this);
		page = _this.attr('cp');

		$('#questions').addClass('vertical segment loading');

		$.get('c.php', {page: page, section:query}).done(function(data){

			$('#questions').removeClass('vertical segment loading');

			$('a.item.active').removeClass('active');
			elm = $('[cp='+page+']');
			elm.addClass('active');

			$('#questions .ui.items').remove();
			$('#questions').append(data);
		});
	});
});

$('.ui.dropdown').dropdown();