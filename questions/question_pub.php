<?php
// The view for the public
$pageTitle = "Question/Public";
$id = sanitize_id($_GET['id']) ?: null;

$QNA = new QNA();

if(!$q = QNA::get_question($id)) {
	// if the id is not in the questions database, try to find it in the comment database.
	if ($q = Comment::getComment($id)) { 
		$q = $q->post_id;
		if($q == $id) $session->message("Page was not found!", "/sha/404.php", "warning");
		Redirect::redirectTo("question.php?id={$q}#{$id}");
	} else {
		$session->message("Page was not found!", "/sha/404.php", "warning");
	}
}

if($q->status != 1) Redirect::redirectTo('404');

$votes_count = QNA::get_votes($id) ?: "0";

$post_date = $q->created;
$post_modified_date = $q->last_modified;

if($post_modified_date > $post_date){
	$edited = " (edited <span id='post-date-ago' title=\"$post_modified_date\">$post_modified_date</span>)";
} else {
	$edited = "";
}
include (ROOT_PATH . 'inc/head.php');
?>
<body>
	<div class="question-page ui container section pub">
		<?= msgs(); ?>
		<div class="ui two column grid">
			<div class="twelve wide column">
				<div class="blog-post" id="<?= $id; ?>">
					<div class="ui grid post-header">
						<div class="two wide column post-avatar">
							<div class="thumbnail small">
								<a href="<?= BASE_URL.'user/'.$q->uid ?>/"><img src="<?= $q->img_path ?>"></a>
							</div>
						</div>
						<div class="nine wide column post-title">
							<h3><a href="/sha/user/<?= $q->uid; ?>/"><?= $q->full_name;?></a></h3>
							<p><a href="/sha/user/<?= $q->uid; ?>/">@<?= $q->username;?></a></p>
							<p class="time"><span id="post-date" title="<?=$post_date;?>"><?= $post_date;?></span>  in <a href="/sha/questions/?section=<?= $q->acr; ?>"><?= $q->fac; ?></a> <?= $edited; ?></p>
						</div>
					</div>
					<br><br>
					<div class="ui left aligned" style="min-height:320px;">
						<div class="ui header">
							<h3 class="blog-post-title"><?= $q->title; ?></h3>
						</div>
						<div class="ui divider"></div>
						<p><?= $q->content; ?></p>
					</div>

					<div class="actions">
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
					</div>
				</div>
			</div>
			<div class="four wide column" style="border-left: 1px #e2e2e2 solid;">
				<div class="sidebar-module sidebar-module-inset">
					<h4>Related questions</h4>
					<div class="ui segment">
						<div class="ui relaxed divided list" id="sidebar-content">
							<?php 
								$items = QNA::get_posts_by_section($q->section, 5, true);
								if(count($items) < 2) echo "<p>There are no other question in this section.</p>";
									else;
								foreach(QNA::get_posts_by_section($q->section, 5, true) as $item){ ?>
								<?php if ($q->id == $item->id) continue; ?>
									<div class="item">
										<div class="content">
											<a href="question.php?id=<?= $item->id; ?>"><?= $item->title; ?></a>
										</div>
										<span id="sidebar-date"><?= $item->created; ?></span>
									</div>
							<?php } ?>
						</div>
					</div>
					<h4>More questions by <?= View::user($q->uid); ?></h4>
					<div class="ui segment">
						<div class="ui relaxed divided list" id="sidebar-content">
							<?php
							$items = QNA::get_posts_by_user($q->uid, 5, true);
							if (count($items) < 2) echo "<p>". View::user($q->uid)." doesn't have any other questions.</p>";
								else;
							 foreach($items as $item){ ?>
							<?php if ($q->id == $item->id) continue; ?>
								<div class="item">
									<div class="content">
										<a href="question.php?id=<?= $item->id; ?>"><?= $item->title; ?></a>
									</div>
									<span id="sidebar-date"><?= $item->created; ?></span>
								</div>
							<?php 
								}  ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<hr>
		<div class="commentz section">
			<p style="font-size: large;"><a href="/sha/login.php">Login</a> or <a href="/sha/signup.php">sign up</a> to comment on this post.</p><br>
			<?php $comments = Comment::get_comments($id); ?>
				<h3>Comments (<?= count($comments) ?: "0"; ?>): </h3>
			<div id="comments">
				<?php 
				if(count($comments) === 0) echo "There is nothing here yet, be the first to comment!";
				else;
					foreach ($comments as $comment):
						$votes = Comment::get_votes($comment->id); 
						$commenter = User::get_user_info($comment->uid);

						$comment_date = $comment->created;
						$comment_modified_date = $comment->last_modified;

						if($comment->last_modified > $comment->created){
							$edited = "(edited <span id='editedDate' title=\"$comment_modified_date\">$comment_modified_date</span>)";
						} else {
							$edited = "";
						}
						?>
						<div class="ui minimal comments">
							<div class="ui comment padded segment" id="<?= $comment->id; ?>">
								<a class="avatar" href="<?= BASE_URL .'user/'.$comment->uid; ?>/">
									<img src="<?= $comment->path; ?>">
								</a>
								<div class="content">
									<?= View::user($comment->uid, false, 'author'); ?>
									<div class="metadata">
										<a class="time" href="question.php?id=<?= $comment->id; ?>"><span id="commentDate" title="<?=$comment_date;?>"><?= $comment_date;?></span></a><?= $edited; ?>
									</div>
									<div class="text">
										<h4><?= $comment->content; ?></h4>
									</div>
									<div class="ui comment dropdown">
										<div class="ui labeled button" tabindex="0">
											<div class="comment-points">
												<a class="comment-vote-btn-pub">
													<i class="heart circular icon"></i>
												</a>
												<span class="comment-votes-count"><?=$votes;?> </span>
											</div>
										</div>
										<div class="menu">
											<div class="ui error message">
												<p>You must <a href="/sha/login.php">login</a> to like this comment.</p>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>
				<?php endforeach; ?>
			<script>
				$('.ui.comment.dropdown').dropdown({on: 'click'}).dropdown({'direction':'upward'});
			</script>
			</div>
		</div>
	</div>
	<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>