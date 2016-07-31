$('.ui.form').form({
	fields: {
		title: {
			identifier: 'title',
			rules: [
			{
				type   : 'empty',
				prompt : 'Title can\'t be empty'
			},
			{
				type   : 'minLength[5]',
				prompt : 'Title must be longer than 5 characters'
			},
			{
				type   : 'maxLength[50]',
				prompt : 'Title must be less than 50 characters'
			}
			]
		},
		content: {
			identifier: 'content',
			rules: [
			{
				type   : 'empty',
				prompt : 'Content can\'t be empty'
			},
			{
				type   : 'minLength[10]',
				prompt : 'Content must be at least 10 characters long'
			}
			]
		},
		faculty_id: {
			identifier: 'faculty_id',
			rules: [
			{
				type   : 'minCount[1]',
				prompt : 'Please select a section'
			}
			]
		}
	},
	inline : true,
	duration: '15',
	on     : 'change',
  templates: {

    // template that produces label
    prompt: function(errors) {
      return $('<div/>')
        .addClass('ui basic red pointing prompt label')
      ;
    }
  },
});

$('.selection.dropdown').dropdown();