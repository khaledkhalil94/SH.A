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
							$self = ($value['u_id'] === $value['p_id']) ? true : false;?>
							<div class="ui segment activity-view">
								<div class="header user-details">
									<div class="ui image mini">
										<a href="<?=BASE_URL."user/{$value['u_id']}/"?>"><img src="<?= $value['p_path'] ?>"></a>
									</div>
									<?php if($self){ ?>
									<div class="summary">
										<a href="<?=BASE_URL."user/{$value['u_id']}/"?>"><?= $value['u_fullname'] ?></a>&nbsp;Posted&nbsp;
										<div class="time"><a href="<?= BASE_URL ."user/posts/". $value['id'] ?>/">6 min ago</a></div>
									</div>
									<?php } else { ?>
									<div class="summary">
										<a href="<?=BASE_URL."user/{$value['p_id']}/"?>"><?= $value['p_fullname']?></a>&nbsp;Posted on&nbsp;<a href="<?=BASE_URL."user/{$value['u_id']}/"?>"><?= $value['u_fullname'] ?></a>'s profile&nbsp;
										<div class="time"><a href="<?= BASE_URL ."user/posts/{$value['id']}/"?>">6 min ago</a></div>
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
												<i class="like red icon"></i>5 Like
											</a>
										</div>
										<div class="post-comments">
											<a class="comments">
												<i class="comments blue icon"></i> 5 Comments
											</a>
										</div>
									</div>
								</div>
							</div>
						<?php break;

						case 'cmt': ?>
							<div class="ui segment comment-view">
								<div class="header user-details">
									<div class="ui image mini">
										<a href="<?=BASE_URL."user/{$value['uid']}/"?>"><img src="<?= $value['path'] ?>"></a>
									</div>
									<div class="summary">
										<a href="<?=BASE_URL."user/{$value['u_id']}/"?>"><?= $value['fullname'] ?></a>&nbsp;Commented on a&nbsp;<a href="<?= $value['post_id'] ?>">post</a>&nbsp;
										<div class="time"><a href="<?= BASE_URL ."user/posts/". $value['id'] ?>/">6 min ago</a></div>
									</div>
								</div>
								<div class="content">
									<div class="extra text">
										<p><?= $value['content']?></p>
									</div>
									<div class="meta post-footer">
										<div class="post-points">
											<a class="like">
												<i class="like red icon"></i>5 Like
											</a>
										</div>
									</div>
								</div>
							</div>
						<?php break;

						case 'qs': 
						$id = BASE_URL."questions/question.php?id={$value['id']}";
						$uid = BASE_URL."user/{$value['uid']}/";
						?>
							<div class="ui segment question-view">
								<div class="header user-details">
									<div class="ui image mini">
										<a href="<?=$uid ?>"><img src="<?= $value['path'] ?>"></a>
									</div>
									<div class="summary">
										<a href="<?=$uid ?>"><?= $value['firstName'] ?></a>&nbsp;Asked a new&nbsp;<a href="<?=$id?>">question</a>&nbsp;
										<div class="time"><a href="<?= $id?>">6 min ago</a></div>
									</div>
								</div>
								<div class="content">
									<div class="extra text">
										<p><?= ctrim($value['content'], 150, true, $id)?></p>
									</div>
									<div class="meta post-footer">
										<div class="post-points">
											<a class="like">
												<i class="like red icon"></i>5 Likes
											</a>
										</div>
										<div class="post-comments">
											<a href="<?=$id?>" class="comments">
												<i class="comments blue icon"></i> 5 Comments
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
										<div class="date"><?= $value['date'] ?></div>
									</div>
								</div>
							</div>
						<?php break;

						case 'ps': ?>
							<div class="ui segment like-feed">
								<div class="header user-details">
									<i class="thumbs up blue large icon"></i>
									<div class="summary">
										<a href="<?= BASE_URL."user/{$value['user_id']}/" ?>"><?= $value['firstName'] ?></a>&nbsp;Liked a&nbsp;<a href="<?= $value['id'] ?>">post or comment</a>&nbsp;
										<div class="date"><?= $value['date'] ?></div>
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
</html>