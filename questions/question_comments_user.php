<?php $comments = Comment::get_comments($id); ?>
<br>
<h3>Comments (<span id="commentscount"><?= count($comments); ?></span>): </h3>
<form class="ui reply form" action="">
	<div class="field">
		<textarea name="content" id="comment-submit-textarea" rows="2" placeholder="Add a new comment.."></textarea>
	</div>
	<input type="hidden" name="post_id" value="<?= $id; ?>" >
	<input type="hidden" name="comment_token" value="<?= Token::generateToken(); ?>" >
	<button name="comment" id="subcomment" style="display:none;" class="ui blue submit disabled icon button">Submit</button>
</form>
<hr>
<div id="comments">
<?php if(count($comments) === 0) echo "<span id=\"emptycmt\">There is nothing here yet, be the first to comment!</span>";
		else;
		foreach ($comments as $comment):
			$voted = QNA::has_voted($comment->id, USER_ID);
			$votes = Comment::get_votes($comment->id); 
			$self = $comment->uid === USER_ID;

			$rpsc = QNA::get_reports_count($comment->id) ?: false;

			$comment_date = $comment->created;
			$comment_edited_date = $comment->last_modified;

			if($comment->last_modified > $comment->created){
				$edited = "(edited <span id='editedDate' title=\"$comment_edited_date\">$comment_edited_date</span>)";
			} else {
				$edited = "";
			}
			?>
				<?php if($session->adminCheck()) {?>
				<a style="color:red;" href="/sha/admin/report.php?id=<?= $comment->id; ?>">
				 </a>
				<?php } ?>

				<div class="ui minimal comments">
					<div class="ui comment padded segment" id="<?= $comment->id; ?>" comment-id="<?= $comment->id; ?>">
						<a class="" href="/sha/user/<?= $comment->uid; ?>/">
							<img class="" src="<?= $comment->path; ?>">
						</a>
						<div class="content">
							<a class="author user-title" user-id="<?= $user->id; ?>" href="<?= BASE_URL."user/".$comment->uid; ?>/"><?= $comment->fullname;?></a>
							<div class="metadata">
								<a class="time" href="question.php?id=<?= $comment->id; ?>"><span id="commentDate" title="<?=$comment_date;?>"><?= $comment_date;?></span></a><?= $edited; ?>
							</div>
							<?php if($rpsc): ?>
							<div class='cmt_rpts'>
								<a title="Reports" href="/sha/admin/report.php?id=<?= $comment->id; ?>"><span class="cmt_rpt_count"><?= $rpsc; ?> </span><i class="ui icon flag red"></i></a>
							</div>
							<?php endif; ?>
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
							<div class="text">
								<h4><?= $comment->content; ?></h4>
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

						</div>
					</div>
				</div>

		<?php endforeach; ?>
</div>
<?php require_once($_SERVER["DOCUMENT_ROOT"] . '/sha/controllers/modals/post.delete.php'); ?>
<?php require_once($_SERVER["DOCUMENT_ROOT"] . '/sha/controllers/modals/q.report.php'); ?>
<?php require_once($_SERVER["DOCUMENT_ROOT"] . '/sha/controllers/modals/q.pub.php'); ?>
<?php require_once($_SERVER["DOCUMENT_ROOT"] . '/sha/controllers/modals/q.unpb.php'); ?>
<?php require_once($_SERVER["DOCUMENT_ROOT"] . '/sha/controllers/modals/comment.delete.php'); ?>
<script>
$('.ui.dropdown').dropdown({on: 'click'});
</script>