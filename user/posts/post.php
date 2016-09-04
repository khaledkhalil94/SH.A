<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/src/init.php");

$PostID = sanitize_id($_GET['post_id']);
if(!$PostID) Redirect::redirectTo();

$post = new Post();
$post = $post->get_post($PostID);
if(!is_object($post)) Redirect::redirectTo();

$self_p = $post->user_id === $post->poster_id ? true : false;

$self = $post->user_id === USER_ID ? true : false;

if(USER_ID){
	$voted = QNA::has_voted($PostID, USER_ID);
}

$votes_count = QNA::get_votes($PostID) ?: "0";


include (ROOT_PATH . "inc/head.php");
?>

<body>
	<div class="user-feed post container section">
		<div class="feed-post ui segment" id="post-page" post-id="<?= $post->id; ?>">
			<?php if ($self): ?>
			<div title="Actions" class="ui pointing dropdown" id="post-actions">
				<i class="setting link large icon"></i>
				<div class="menu">
					<div class="item" id="post-delete">
						<a class="ui a">Delete</a>
					</div>
				</div>
			</div>
			<?php endif; ?>	
			<div class="ui grid post-header">
				<div class="three wide column post-avatar">
					<a href="/sha/user/<?= $post->r_id; ?>/"><img class="ui avatar tiny image" src="<?= $post->img_path ?>"></a>
				</div>
				<div class="thirteen wide column post-title">

				<?php if($self_p){ ?>
					<div class="meta">

						<span class="post-header">
							<p><a href="/sha/user/<?= $post->r_id; ?>/"><?= $post->firstName;?> </a></p>
						</span> 

						<span id="post-date" class="time" title="<?=$post->date;?>"><?= $post->date;?></span>
					</div>
				<?php } else { ?>
					<div class="meta">
						<span class="post-header" style="margin-bottom: -5px;">
							<p><a href="/sha/user/<?= $post->r_id; ?>/"><?= $post->firstName;?></a></p>

							<p>
								<i class="mdi mdi-menu-right"></i>
								<a href="/sha/user/<?= $post->uid; ?>/"><?= $post->r_fn; ?></a>
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
			</div>
			<br><br>
			<div class="actions">
				<?php if(!$session->is_logged_in()){  ?>
				<div class="ui dropdown ">
					<div class="ui labeled button" tabindex="0">
						<div class="ui grey button" id="votebtn-pub">
							<i class="heart icon"></i><span>Like</span>
						</div>
						<a class="ui basic grey left pointing label" id="votescount"><?= $votes_count; ?></a>
					</div>
					<div class="menu">
						<div class="ui error message">
							<p>You must <a href="/sha/login.php">login</a> to like this post.</p>
						</div>
					</div>
					<script>
						$('.ui.dropdown').dropdown({on: 'click'}).dropdown({'direction':'upward'});
					</script>
				</div>
				<?php } elseif($voted){ ?>
				<div class="ui labeled button" tabindex="0">
					<div class="ui red button voted" id="votebtn">
						<i class="heart icon"></i><span>unLike</span>
					</div>
					<a class="ui basic red left pointing label" id="votescount"><?= $votes_count; ?></a>
				</div>
				<?php } else {?>
				<div class="ui labeled button" tabindex="0">
					<div class="ui grey button" id="votebtn">
						<i class="heart icon"></i><span>Like</span>
					</div>
					<a class="ui basic grey left pointing label" id="votescount"><?= $votes_count; ?></a>
				</div>
				<?php } ?>
			</div>
			<br><hr>
			<div class="ui comments">
				<div class="comment">
					<a class="avatar">
						<img src="<?= DEF_PIC; ?>">
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
						<img src="<?= DEF_PIC; ?>">
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
								<img src="<?= DEF_PIC; ?>">
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
						<img src="<?= DEF_PIC; ?>">
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
	<script src="/sha/scripts/post.js"></script>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>


<div class="ui small modal post delete">
	<div class="ui segment">
		<div class="header">
			<h3>DELETE</h3>
		</div>
		<div class="content">
			<div class="description">
				<p>This post and all it's comments will be deleted permanently , are you sure you want to continue ?</p><br>
			</div>
		</div>
		<div class="actions">
			<div class="ui white deny button">
				Cancel
			</div>
			<div class="ui blue button" id="post-confirmDel">
				Delete
			</div>
		</div>
	</div>
</div>