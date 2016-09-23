<?php
require_once ("src/init.php");
$pageTitle = "Home Page";
$sec = "index";
include (ROOT_PATH . "inc/head.php"); 

$post = new Post();

if($session->is_logged_in()){
	$feed = $post->get_stream();
	
	usort($feed, 'date_compare');
	$feed = array_reverse($feed);
	
	$feed = array_slice($feed, 0, 30); // limit the feed items to 30

	$nf = [];
}


?>
<body>
	<div class="ui ui container section main-page">
		<div class="content front">
			<?= msgs(); ?>
			<div class="news-feed">
				<?php 
				if(!$session->is_logged_in()){
					echo "<p>Login now and see what's on your new's feed!</p>";
				} else {
					foreach ($feed as $value) {
						switch ($value['type']) {
							case 'ac': 
								$postID = $value['id'];

								$self = ($value['u_id'] === $value['p_id']) ? true : false;
								$me = ($value['p_id'] == USER_ID) ? true :false;
								$to_self = ($value['u_id'] === USER_ID) ? true : false;
								$post_id = $value['id'];
								$poster_id = BASE_URL."user/{$value['p_id']}/";

								$p_count = QNA::get_votes($postID) ?: "0";
								$commentsCount = QNA::get_Qcomments($postID) ? count(QNA::get_Qcomments($postID)) : "0";
								?>
								<div class="ui segment activity-view">
									<div class="header user-details">
										<div class="ui image mini">
											<a href="<?=$poster_id?>"><img src="<?= $value['p_path'] ?>"></a>
										</div>
										<?php if($self){ ?>
										<div class="summary">
											<p><?= $me ? 'You' : View::user($value['p_id'], true) ?> posted </p>&nbsp;
											<div class="time"><?= View::postDate($post_id) ?></div>
										</div>
										<?php } elseif($to_self) { ?>
										<div class="summary">
											<?= $me ? 'You' : View::user($value['p_id'], true) ?>&nbsp;Posted on your profile&nbsp;
											<div class="time"><?= View::postDate($post_id) ?></div>
										</div>
										<?php } else { ?>
										<div class="summary">
											<?= $me ? 'You' : View::user($value['p_id'], true) ?>&nbsp;Posted on&nbsp;
											<?= View::user($value['u_id'], true) ?>'s profile&nbsp;
											<div class="time"><?= View::postDate($post_id) ?></div>
										</div>
										<?php } ?>
									</div>
									<div class="content">
										<div class="text">
											<p><?= $value['content'] ?></p>
										</div>
										<div class="meta post-footer">
											<div class="post-points">
												<a class="like">
													<i class="like red icon"></i><?= $p_count ?><?= ($p_count > 1) ? " Likes" : ($p_count == 0) ? " Likes" : " Like"; ?>
												</a>
											</div>
											<div class="post-comments">
												<a class="comments">
													<i class="comments blue icon"></i><?= $commentsCount ?><?= ($commentsCount > 1) ? " Comments" : ($commentsCount == 0) ? " Comments" : " Comment"; ?> 
												</a>
											</div>
										</div>
									</div>
								</div>
							<?php break;

							case 'cmt': 
								$postID = $value['id'];
								$type = Post::PorQ($value['post_id']);

								if($type == "q"){

									$post_id = BASE_URL."questions/question.php?id={$value['post_id']}";
									$p = "question";

								} elseif($type == 'p') {

									$post_id = BASE_URL."user/posts/{$value['post_id']}/";
									$p = "post";
								} else {
									continue;
								}


								$p_count = QNA::get_votes($postID) ?: "0";
								?>
								<div class="ui segment comment-view">
									<div class="header user-details">
										<div class="ui image mini">
											<a href="<?=BASE_URL."user/{$value['uid']}/"?>"><img src="<?= $value['path'] ?>"></a>
										</div>
										<div class="summary">
											<?= View::user($value['uid'], true) ?>&nbsp;Commented on a&nbsp;<a href="<?= $post_id ?>"><?=$p?></a>&nbsp;
											<div class="time"><?= View::postDate($value['post_id']) ?></div>
										</div>
									</div>
									<div class="content">
										<div class="extra text">
											<?= $value['content']?>
										</div>
										<div class="meta post-footer">
											<div class="post-points">
												<a class="like">
													<i class="like red icon"></i><?= $p_count ?><?= ($p_count > 1) ? " Likes" : ($p_count == 0) ? " Likes" : " Like"; ?>
												</a>
											</div>
										</div>
									</div>
								</div>
							<?php break;

							case 'qs': 
								$self = ($value['uid'] === USER_ID) ? true : false;
								$postID = $value['id'];
								$id = BASE_URL."questions/question.php?id={$postID}";
								$uid = BASE_URL."user/{$value['uid']}/";

								$p_count = QNA::get_votes($postID) ?: "0";
								$commentsCount = QNA::get_Qcomments($postID) ? count(QNA::get_Qcomments($postID)) : "0";
								?>
								<div class="ui segment question-view">
									<div class="header user-details">
										<div class="ui image mini">
											<a href="<?=$uid ?>"><img src="<?= $value['path'] ?>"></a>
										</div>
										<div class="summary">
											<?= $self ? "<p>You</p>" : View::user($value['uid'], true); ?>
											&nbsp;asked a new&nbsp;<a href="<?=$id?>">question</a>&nbsp;
											<div class="time"><?= View::postDate($value['id']) ?></div>
										</div>
									</div>
									<div class="content">
										<div class="extra text">
											<h3><a href="<?=$id?>" class="title"><?= $value['title'] ?></a></h3>
										</div><br>
										<div class="meta post-footer">
											<div class="post-points">
												<a href="<?=$id?>" class="like">
													<i class="heart red icon"></i><?= $p_count ?><?= ($p_count > 1) ? " Likes" : ($p_count == 0) ? " Likes" : " Like"; ?> 
												</a>
											</div>
											<div class="post-comments">
												<a href="<?=$id?>" class="comments">
													<i class="comments blue icon"></i><?= $commentsCount ?><?= ($commentsCount > 1) ? " Comments" : ($commentsCount == 0) ? " Comments" : " Comment"; ?> 
												</a>
											</div>
										</div>
									</div>
								</div>
							<?php break;

							case 'fl': ?>
								<div class="ui segment follow-feed">
									<div class="header user-details">
										<i class="mdi mdi-account-multiple-plus"></i>
										<div class="summary">
											<?= View::user($value['follower_id'], true) ?>&nbsp;Followed&nbsp;
											<?= View::user($value['user_id'], true) ?>&nbsp;
											<div class="time"><span class="timestamp" title="<?= $value['date'] ?>"><?= $value['date'] ?></span></div>
										</div>
									</div>
								</div>
							<?php break;

							case 'ps': 

								$id = $value['post_id'];

								$type = Post::PorQ($id);

								if($type == "q"){
									$post_id = BASE_URL."questions/question.php?id={$id}";
									$pc = "question";
								} elseif($type == "p") {
									$post_id = BASE_URL."user/posts/{$id}/";
									$pc = "post";
								} elseif($type == "c") {
									$post_id = BASE_URL."questions/question.php?id={$id}";
									$pc = "comment";
								} else {
									continue;
								}
								?>
								<div class="ui segment like-feed">
									<div class="header user-details">
										<i class="thumbs up blue large icon"></i>
										<div class="summary">
											<?= View::user($value['user_id'], true) ?>&nbsp;Liked a&nbsp;<a href="<?= $post_id ?>"><?= $pc ?></a>&nbsp;
											<div class="time"><span class="timestamp" title="<?= $value['date'] ?>"><?= $value['date'] ?></span></div>
										</div>
									</div>
								</div>
							<?php break;
							
							default:
								
								break;
						}
					}
				}
				?>
			</div>
		</div>
	</div>
	<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
<script>
$(function(){
	$('.news-feed .time .timestamp').each(function(index, value) {
		date = $(this).text();
		$(this).text(moment(date).fromNow());
	});
});
</script>
</html>