<?php
require_once ("src/init.php");
$pageTitle = "Home Page";
$sec = "index";
include (ROOT_PATH . "inc/head.php"); 

$post = new Post();

function date_compare($a, $b)
{
	 $t1 = strtotime($a['date']);
	 $t2 = strtotime($b['date']);
	 return $t1 - $t2;
}    

$feed = $post->get_stream();

usort($feed, 'date_compare');
$feed = array_reverse($feed);

$nf = [];

?>
<body>
	<div class="container section main-page">
		<div class="content front">
			<?= msgs(); ?>
			<div class="news-feed">
				<?php 
				foreach ($feed as $value) {
					switch ($value['type']) {
						case 'ac': 
							$postID = $value['id'];

							$self = ($value['u_id'] === $value['p_id']) ? true : false;
							$to_self = ($value['u_id'] === USER_ID) ? true : false;
							$post_id = BASE_URL ."user/posts/{$value['id']}/";
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
										<p>You posted </p>&nbsp;
										<div class="time"><a href="<?= $post_id; ?>"><span class="timestamp"><?= $value['date'] ?></span></a></div>
									</div>
									<?php } elseif($to_self) { ?>
									<div class="summary">
										<a href="<?= $poster_id ?>"><?= $value['p_fullname']?></a>&nbsp;Posted on your profile&nbsp;
										<div class="time"><a href="<?= $post_id; ?>"><span class="timestamp"><?= $value['date'] ?></span></a></div>
									</div>
									<?php } else { ?>
									<div class="summary">
										<a href="<?= $poster_id ?>"><?= $value['p_fullname']?></a>&nbsp;Posted on&nbsp;<a href="<?=BASE_URL."user/{$value['u_id']}/"?>"><?= $value['u_fullname'] ?></a>'s profile&nbsp;
										<div class="time"><a href="<?= $post_id ?>"><span class="timestamp"><?= $value['date'] ?></span></a></div>
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

							if(Post::PorQ($value['post_id']) == "q"){
								$post_id = BASE_URL."questions/question.php?id={$value['post_id']}";
								$p = "question";
							} else {
								$post_id = BASE_URL."user/posts/{$value['post_id']}/";
								$p = "post";
							}

							$id = BASE_URL."questions/question.php?id={$postID}";

							$p_count = QNA::get_votes($postID) ?: "0";
							?>
							<div class="ui segment comment-view">
								<div class="header user-details">
									<div class="ui image mini">
										<a href="<?=BASE_URL."user/{$value['uid']}/"?>"><img src="<?= $value['path'] ?>"></a>
									</div>
									<div class="summary">
										<a href="<?=BASE_URL."user/{$value['uid']}/"?>"><?= $value['fullname'] ?></a>&nbsp;Commented on a&nbsp;<a href="<?= $post_id ?>"><?=$p?></a>&nbsp;
										<div class="time"><a href="<?= $id ?>"><span class="timestamp"><?= $value['date']; ?></span></a></div>
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
										<?php if($self){ ?><p>You</p><?php }else{ ?><a href="<?=$uid ?>"><?= $value['firstName'] ?></a><?php }?>
										&nbsp;asked a new&nbsp;<a href="<?=$id?>">question</a>&nbsp;
										<div class="time"><a href="<?= $id?>"><span class="timestamp"><?= $value['date'] ?></span></a></div>
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
										<a href="<?= BASE_URL."user/{$value['follower_id']}/"?>"><?=$value['f_firstname']?></a>&nbsp;Followed&nbsp;<a href="<?= BASE_URL."user/{$value['user_id']}/"?>"><?=$value['u_firstname']?></a>&nbsp;
										<div class="time"><span class="timestamp"><?= $value['date'] ?></span></div>
									</div>
								</div>
							</div>
						<?php break;

						case 'ps': 

							$id = $value['post_id'];

							if(Post::PorQ($id) === "q"){
								$post_id = BASE_URL."questions/question.php?id={$id}";
								$pc = "question";
							} elseif(Post::PorQ($id) === "p") {
								$post_id = BASE_URL."user/posts/{$id}/";
								$pc = "post";
							} elseif(Post::PorQ($id) === "c") {
								$post_id = BASE_URL."questions/question.php?id={$id}";
								$pc = "comment";
							} else {
								echo "WHAT THE FUCK IS GOING ON";
							}
							?>
							<div class="ui segment like-feed">
								<div class="header user-details">
									<i class="thumbs up blue large icon"></i>
									<div class="summary">
										<a href="<?= BASE_URL."user/{$value['user_id']}/" ?>"><?= $value['firstName'] ?></a>&nbsp;Liked a&nbsp;<a href="<?= $post_id ?>"><?= $pc ?></a>&nbsp;
										<div class="time"><span class="timestamp"><?= $value['date'] ?></span></div>
									</div>
								</div>
							</div>
						<?php break;
						
						default:
							
							break;
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