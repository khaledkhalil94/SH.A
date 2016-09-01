<div class="tab-questions">
<?php foreach($QNA->get_questions_by_user($UserID) AS $q): ?>
	<div class="item">
			<a href='/sha/questions/question.php?id=<?= $q->id ?>'><h4><?= $q->title; ?></h4></a>
			<div class="time" id="post-date"><?= $q->created ?></div>
	</div><hr>
<?php endforeach;?>
</div><br>

<script>
	$('.item').each(function(index, value) {
		$date = $(this).find('#post-date').text();
		$(this).find('#post-date').text(moment($date).fromNow());
	});
</script>