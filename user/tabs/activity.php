<?php 
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/src/init.php");

$posts = $post->get_posts(USER_ID);

?>

<div class="ui relaxed divided items">

<?php foreach ($posts as $post): 
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
			<div class="ui right floated button dropdown">
				<i class="dropdown icon"></i>
				<div class="menu">
					<div class="item">Hide</div>
					<div class="item">Edit</div>
					<div class="item">Delete</div>
					<div class="item">Report</div>
				</div>
			</div>
			<div class="meta">
				<a href="/sha/user/posts/<?= $post->id; ?>/" class="time"><?= $post->date ?></a>
			</div>
			<div class="description">
				<?= $post->content; ?>
			</div><br>
			<div class="extra">
				<span style="cursor:pointer;" class="likes"><i class="heart red icon"></i> 6</span>
				<a href="/sha/user/posts/<?= $post->id; ?>/" style="cursor:pointer;" class="comments"><i class="comments blue icon"></i> 3 comments</a>
			</div>
		</div>
	</div>
<?php endforeach;
?>
</div>


<script>$('.ui.dropdown').dropdown();</script>