<?php require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/init.php");

$QNA = new QNA();
$posts = $QNA->get_saved(USER_ID);
?>

<div class="tab-saved">
<?php foreach($posts AS $post): ?>
	<div class="item" post-id="<?= $post->id ?>">
		<div class="ui grid">
			<div class="fifteen wide column">
				<a href='/questions/question.php?id=<?= $post->id ?>'><h4><?= $post->title; ?></h4></a>
				<div class="time" id="post-date"><?= $post->created ?></div>
			</div>
			<div class="one wide column">
				<i class="remove large link icon" title="Remove this post" id="saved_remove"></i>
			</div>
		</div>
	</div>
	<hr>
<?php endforeach;?>
</div><br>

<script>
	$('.item').each(function(index, value) {
		$date = $(this).find('#post-date').text();
		$(this).find('#post-date').text(moment($date).fromNow());
	});

$(function(){
	$('i.remove.icon').click(function(){

		_elm = $(this).closest('.item');
		id = _elm.attr('post-id');

		$.ajax({
			url: '/controllers/_question.php',
			type: 'post',
			dataType : 'json',
			data: {'action':'unsave', 'id': id},

			success: function(data, status) {

				if(data.status == true) {

					_elm.remove();
				} else { 

				}
			},
			error: function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			}
		}); // end ajax call
	});
});
</script>
