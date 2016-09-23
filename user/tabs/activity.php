<?php 
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/src/init.php");

$post = new Post();

$uid = $_POST['uid'];
$posts = $post->get_posts($uid);
$self = ($_POST['self'] == 'true') ? true : false;
$user = User::get_user_info($uid);

?>



<?php if(!$session->is_logged_in()){
} elseif($self){ ?>
<form class="ui feed-post form" action="#" method="POST">
	<div class="field">
		<textarea name="content" id="feed-submit-textarea" rows="2" style="height:auto;" placeholder="What's going on ?"></textarea>
	</div>
	<input type="hidden" name="uid" value="<?= $uid; ?>" >
	<input type="hidden" name="feed_token" value="<?= Token::generateToken(); ?>" >
	<button name="feed" id="feed-post" class="ui green submit icon button">Post</button>
</form>
<br>
<div class="ui relaxed divided items" id="profile_feed_comments">
<?php
} else { ?>
<form class="ui feed-post form" action="#" method="POST">
	<div class="field">
		<textarea name="content" id="feed-submit-textarea" rows="2" style="height:auto;" placeholder="Say something to <?= $user->firstName ?>"></textarea>
	</div>
	<input type="hidden" name="uid" value="<?= $uid; ?>" >
	<input type="hidden" name="feed_token" value="<?= Token::generateToken(); ?>" >
	<button name="feed" class="ui blue submit icon button">Submit</button>
</form>
<br>
<div class="ui relaxed divided items" id="profile_feed_comments">
<?php 
}
if(empty($posts)){
	echo "<p id='emptycmt'>There's nothing here!</p>";
} else {

foreach ($posts as $post): 

	$votes_count = QNA::get_votes($post->id) ?: "0";

	$cCount = count($comments = Comment::get_comments($post->id));

	$post->img_path = $post->img_path ?: DEF_PIC; 
	?>

		<div class="item">
			<a href="/sha/user/<?= $post->uid ?>/" class="ui tiny image">
				<img src="<?= $post->img_path ?>">
			</a>
			<div class="content">
				<span>
					<h4 style="display:inline;">
						<a href="/sha/user/<?= $post->uid ?>/">@<?= $post->username ?></a>
					</h4>
				</span>
				<div class="meta" style="display:inline;">
					<a href="/sha/user/posts/<?= $post->id; ?>/" class="time" id="post-date"><?= $post->date ?></a>
				</div>
				<div class="description">
					<?= $post->content; ?>
				</div><br>
				<div class="extra">
					<span style="cursor:pointer;" class="likes"><i class="heart red icon"></i> <?= $votes_count ?></span>
					<a href="/sha/user/posts/<?= $post->id; ?>/" style="cursor:pointer;" class="comments"><i class="comments blue icon"></i> <?= $cCount ?> comments</a>
				</div>
			</div>
		</div>
	<?php 
endforeach;
}
?>
</div>

<script>
$('.ui.dropdown').dropdown();

$('.item').each(function(index, value) {
	$date = $(this).find('#post-date').text();
	$(this).find('#post-date').text(moment($date).fromNow());
});

</script>
<script src='<?= BASE_URL ?>scripts/feed.js'></script>
