<?php
// The view for the public
$pageTitle = "Question/Public";
include (ROOT_PATH . 'inc/head.php');
$id = sanitize_id($_GET['id']) ?: null;

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

if($q->status != 1 && !($session->adminCheck() || $session->userCheck($q->uid))) Redirect::redirectTo('404');

$votes_count = QNA::get_votes($id) ?: "0";

$post_date = $q->created;
$post_modified_date = $q->last_modified;

$imgPath = $q->img_path ?: DEF_PIC;

if($post_modified_date > $post_date){
	$edited = " (edited <span id='post-date-ago' title=\"$post_modified_date\">$post_modified_date</span>)";
} else {
	$edited = "";
}
?>
<body>
	<div class="container section pub">
		<?= msgs(); ?>
		<div class="ui two column grid">
			<div class="twelve wide column">
				<div class="blog-post" id="<?= $id; ?>">
					<div class="ui grid post-header">
						<div class="two wide column post-avatar">
							<a href="/sha/user/<?= $q->uid; ?>/"><img class="ui avatar tiny image" src="<?= $imgPath; ?>"></a>
						</div>
						<div class="nine wide column post-title">
							<h3><a href="/sha/user/<?= $q->uid; ?>/"><?= $q->full_name;?></a></h3>
							<p><a href="/sha/user/<?= $q->uid; ?>/">@<?= $q->username;?></a></p>
							<p class="time"><span id="post-date" title="<?=$post_date;?>"><?= $post_date;?></span>  in <?= $q->fac; ?> <span id="post-date-ago"><?= $edited; ?></span></p>
						</div>
					</div>
					<br><br>
					<div class="ui left aligned container" style="min-height:320px;">
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
						<div class="ui relaxed divided list">
							<?php foreach(QNA::get_content($q->section) as $item){ ?>
								<?php if ($q->id != $item->id){ ?>
									<div class="item">
										<div class="content">
											<a href="question.php?id=<?= $item->id; ?>"><?= $item->title; ?></a>
										</div>
									</div>
								<?php } ?>
							<?php } ?>
						</div>
					</div>
					<h4>More questions by <a href="/sha/user/<?= $q->uid; ?>/"><?= $q->full_name; ?></a></h4>
					<div class="ui segment">
						<div class="ui relaxed divided list">
							<?php
							$items = QNA::get_content_by_user($q->uid);
							if (count($items) < 2) {
								echo "<p>This user doesn't have any other questions.</p>";
							} else {
								foreach($items as $item){ ?>
									<?php if ($q->id != $item->id){ ?>
										<div class="item">
											<div class="content">
												<a href="question.php?id=<?= $item->id; ?>"><?= $item->title; ?></a>
											</div>
										</div>
									<?php 
									} 
								}
							} ?>
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
				if(count($comments) === 0) {
					echo "There is nothing here yet, be the first to comment!";
				} else {
					foreach ($comments as $comment):
						$votes = Comment::get_votes($comment->id); 
						$commenter = Student::get_user_info($comment->uid);

						$img_path = Images::get_profile_pic(Student::get_user_info($comment->uid));

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
								<a class="avatar" href="/sha/user/<?= $comment->uid; ?>/">
									<img src="<?= $img_path; ?>">
								</a>
								<div class="content">
									<a class="author" href="<?= BASE_URL."user/".$commenter->id; ?>/"><?= $commenter->full_name;?></a>
									<div class="metadata">
										<a class="time" href="question.php?id=<?= $comment->id; ?>"><span id="commentDate" title="<?=$comment_date;?>"><?= $comment_date;?></span></a><?= $edited; ?>
									</div>
									<div class="text">
										<h4><?= $comment->content; ?></h4>
									</div>

									<div class="ui dropdown ">
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
										<script>
											$('.ui.dropdown').dropdown({on: 'click'}).dropdown({'direction':'upward'});
										</script>
									</div>

								</div>
							</div>
						</div>

				<?php endforeach;
				} ?>
			</div>
		</div>
	</div>
	<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>