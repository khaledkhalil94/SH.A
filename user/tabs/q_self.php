<div class="tab-questions">
<?php 

$QNA = new QNA();
$questions = $QNA->get_questions_by_user(USER_ID);
foreach($questions AS $q): ?>
	<div class="item">
		<div class="ui grid">
			<div class="fourteen wide column">
				<a href='/sha/questions/question.php?id=<?= $q->id ?>'><h4><?= $q->title; ?></h4></a>
				<div class="time" id="post-date"><?= $q->created ?></div>
			</div>
			<div class="two wide column">
			
				<?php if($q->status == "1") { ?>
						<p class="status" title="Public"><?= $pub ?></p>
					<?php } else { ?>
						<p class="status" title="Private"><?= $unpub ?></p>
					<?php } ?>
			</p>
			</div>
		</div>
	</div><hr>
<?php endforeach;?>
</div><br>
<div class="legend"><?= $pub ?> = Public <br> <?= $unpub ?> = Private</div>

<script>
	$('.item').each(function(index, value) {
		$date = $(this).find('#post-date').text();
		$(this).find('#post-date').text(moment($date).fromNow());
	});
</script>