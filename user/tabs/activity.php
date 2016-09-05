<?php 
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/src/init.php");

$post = new Post();

$uid = $_GET['uid'];
$posts = $post->get_posts($uid);

$user = User::get_user_info($uid);
?>

<div class="ui relaxed divided items">

<?php if(!$session->is_logged_in()){
} elseif($uid === USER_ID){ ?>
<form class="ui reply form" action="">
	<div class="field">
		<textarea name="content" id="comment-submit-textarea" rows="2" style="height:auto;" placeholder="What's going on ?"></textarea>
	</div>
	<input type="hidden" name="post_id" value="<?= $id; ?>" >
	<input type="hidden" name="comment_token" value="<?= Token::generateToken(); ?>" >
	<button name="comment" id="subcomment" class="ui blue submit icon button">Submit</button>
</form>
<br>
<?php
} else { ?>
<form class="ui reply form" action="">
	<div class="field">
		<textarea name="content" id="comment-submit-textarea" rows="2" style="height:auto;" placeholder="Say something to <?= $user->firstName ?>"></textarea>
	</div>
	<input type="hidden" name="post_id" value="<?= $id; ?>" >
	<input type="hidden" name="comment_token" value="<?= Token::generateToken(); ?>" >
	<button name="comment" id="subcomment" class="ui blue submit icon button">Submit</button>
</form>
<br>
<?php 
}
if(empty($posts)){
	echo "<p>There's nothing here!</p>";
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
			<a href="/sha/user/<?= $post->uid ?>/" class="header">
				<span><h4><?= $post->firstName; ?></h4></span>
			</a>
				<span><h5 style="display: inline;"><a href="/sha/user/<?= $post->uid ?>/">@<?= $post->username ?></a></h5></span>
			<div class="meta">
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