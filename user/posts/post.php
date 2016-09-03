<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/src/init.php");

$PostID = sanitize_id($_GET['post_id']);
if(!$PostID) Redirect::redirectTo();

$post = $post->get_post($PostID);
if(!is_object($post)) Redirect::redirectTo();

$self_p = $post->user_id === $post->poster_id ? true : false;

include (ROOT_PATH . "inc/head.php");
?>

<body>
	<div class="user-feed post container section">
		<div class="feed-post ui segment" id="<?= $post->id; ?>">
			<div class="ui grid post-header">
				<div class="three wide column post-avatar">
					<a href="/sha/user/<?= $post->id; ?>/"><img class="ui avatar tiny image" src="<?= $post->img_path ?>"></a>
				</div>
				<div class="thirteen wide column post-title">

				<?php if($self_p){ ?>
					<div class="meta">

						<span class="post-header">
							<p><a href="/sha/user/<?= $post->id; ?>/"><?= $post->firstName;?> </a></p>
						</span> 

						<span id="post-date" class="time" title="<?=$post->date;?>"><?= $post->date;?></span>
					</div>
				<?php } else { ?>
					<div class="meta">
						<span class="post-header" style="margin-bottom: -5px;">
							<p><a href="/sha/user/<?= $post->id; ?>/"><?= $post->firstName;?></a></p>

							<p>
								<i style="font-size: x-large;" class="mdi mdi-menu-right"></i>
								<a href="/sha/user/<?= $post->id; ?>/">@<?= $post->user_id; ?></a>
							</p>
						</span> 

						<span id="post-date" class="time" title="<?=$post->date;?>"><?= $post->date;?></span>
					</div>
				<?php } ?>
					<br>
					<div class="ui left aligned container">
						<?php if($post->status == "2"){ ?>
						<div class="ui warning message">
							This post is private, only you can see it, you can change that by clicking <a id="post-publish" href="#"> here.</a> 
						</div>
						<?php } ?>				
						<p><?= $post->content; ?></p>
					</div>
				</div>
			</div><br><hr>
			<div class="ui comments">
				<div class="comment">
					<a class="avatar">
						<img src="/images/avatar/small/matt.jpg">
					</a>
					<div class="content">
						<a class="author">Matt</a>
						<div class="metadata">
							<span class="date">Today at 5:42PM</span>
						</div>
						<div class="text">
							How artistic!
						</div>
						<div class="actions">
							<a class="reply">Reply</a>
						</div>
					</div>
				</div>
				<div class="comment">
					<a class="avatar">
						<img src="/images/avatar/small/elliot.jpg">
					</a>
					<div class="content">
						<a class="author">Elliot Fu</a>
						<div class="metadata">
							<span class="date">Yesterday at 12:30AM</span>
						</div>
						<div class="text">
							<p>This has been very useful for my research. Thanks as well!</p>
						</div>
						<div class="actions">
							<a class="reply">Reply</a>
						</div>
					</div>
					<div class="comments">
						<div class="comment">
							<a class="avatar">
								<img src="/images/avatar/small/jenny.jpg">
							</a>
							<div class="content">
								<a class="author">Jenny Hess</a>
								<div class="metadata">
									<span class="date">Just now</span>
								</div>
								<div class="text">
									Elliot you are always so right :)
								</div>
								<div class="actions">
									<a class="reply">Reply</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="comment">
					<a class="avatar">
						<img src="/images/avatar/small/joe.jpg">
					</a>
					<div class="content">
						<a class="author">Joe Henderson</a>
						<div class="metadata">
							<span class="date">5 days ago</span>
						</div>
						<div class="text">
							Dude, this is awesome. Thanks so much
						</div>
						<div class="actions">
							<a class="reply">Reply</a>
						</div>
					</div>
				</div>
				<form class="ui reply form">
					<div class="field">
						<textarea></textarea>
					</div>
					<div class="ui blue labeled submit icon button">
						<i class="icon edit"></i> Add Reply
					</div>
				</form>
			</div>

		</div>
	</div>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
