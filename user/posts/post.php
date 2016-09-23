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

$comments = Comment::get_comments($post->id);

include (ROOT_PATH . "inc/head.php");
?>

<body>
	<div class="user-feed post ui container section">
		<div class="feed-post ui segment" id="post-page" post-id="<?= $post->id; ?>">
			<?php if ($self || $post->poster_id === USER_ID): ?>
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

						<span id="post-date" class="time" title="<?=$post->date;?>"><?= get_timeago($post->date);?></span>
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

						<span id="post-date" class="time" title="<?=$post->date;?>"><?= get_timeago($post->date);?></span>
					</div>
				<?php } ?>
					<br>
					<div class="ui left aligned">
						<?php if($post->status == "2"){ ?>
						<div class="ui warning message">
							This post is private, only you can see it, you can change that by clicking <a id="post-publish" href="#"> here.</a> 
						</div>
						<?php } ?>				
						<p><?= $post->content; ?></p>
					</div>
				</div>
			</div>
			<br><br><hr><br>
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
				<form class="ui reply form" action="">
					<div class="field">
						<textarea name="content" id="comment-submit-textarea" rows="2" placeholder="Add a new comment.."></textarea>
					</div>
					<input type="hidden" name="post_id" value="<?= $id; ?>" >
					<input type="hidden" name="comment_token" value="<?= Token::generateToken(); ?>" >
					<button name="comment" id="subcomment" style="display:none;" class="ui blue submit disabled icon button">Submit</button>
				</form>
				<br>
				<div id="comments">
					<?php foreach ($comments as $c):
						$voted = QNA::has_voted($c->id, USER_ID);
						$votes = Comment::get_votes($c->id);
						$self = $c->uid === USER_ID;

						if($c->last_modified > $c->created){
							$edited = "(edited <span id='editedDate' title=\"$c->created\">$c->created</span>)";
						} else {
							$edited = "";
						}

						$c->path = $c->path ?: DEF_PIC; 
					?>
					<div class="ui minimal comments">
						<div class="ui comment padded segment" id="<?= $c->id; ?>" comment-id="<?= $c->id; ?>">
							<a class="" href="/sha/user/<?= $c->uid; ?>/">
								<img class="" src="<?= $c->path; ?>">
							</a>
							<div class="content">
								<a class="author user-title" user-id="<?= $user->id; ?>" href="<?= BASE_URL."user/".$c->uid; ?>/"><?= $c->fullname;?></a>
								<div class="metadata">
									<a class="time" href="question.php?id=<?= $c->id; ?>"><span id="post-date" title="<?=$c->created;?>"><?= $c->created;?></span></a><?= $edited; ?>
								</div>
								<div class="text">
									<h4><?= $c->content; ?></h4>
								</div>
								<div class="ui fitted divider"></div>
								<?php if($voted){ ?>
									<div class="comment-points">
										<a class="comment-vote-btn voted"><i class="heart small circular red icon"></i></a>
										<span class="comment-votes-count"><?=$votes;?></span>
									</div>
								<?php } else { ?>
									<div class="comment-points">
										<a class="comment-vote-btn"><i class="heart small circular icon"></i></a><span class="comment-votes-count"><?=$votes;?> </span>
									</div>
								<?php } ?>
								<div title="Actions" class="ui pointing dropdown" id="comment-actions">
									<i class="ellipsis link big horizontal icon"></i>
									<div class="menu">
										<?php if ($self || $session->adminCheck()) { ?>
											<div class="item" id="edit">
												<a class="edit">Edit</a>
											</div>
											<div class="item" id="del">
												<a class="delete">Delete</a>
											</div>
										<?php } ?>
										<?php if (!$self) { ?>
											<div class="item" id="post_report">
												<a class="report">Report</a>
											</div>
											<div class="item" id="comment_hide">
												<a class="report">Hide</a>
											</div>
										<?php } ?>
									</div>
								</div>

							</div>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
	<script src="/sha/scripts/post.js"></script>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>

<?php require_once($_SERVER["DOCUMENT_ROOT"] . '/sha/controllers/modals/post.delete.php'); ?>
<?php require_once($_SERVER["DOCUMENT_ROOT"] . '/sha/controllers/modals/comment.delete.php'); ?>
<?php require_once($_SERVER["DOCUMENT_ROOT"] . '/sha/controllers/modals/q.report.php'); ?>
<script src="<?= BASE_URL ?>scripts/comment.js"></script>
<script>
$('.metadata').each(function(index, value) {
	$date = $(this).find('#post-date').text();
	$(this).find('#post-date').text(moment($date).fromNow());
});
</script>