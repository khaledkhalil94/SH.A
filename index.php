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
	<div class="container section">
		<div class="content">
			<?= msgs(); ?>
			<div class="ui feed">
				<?php 
				foreach ($feed as $value) {
					switch ($value['type']) {
						case 'ac': ?>
						<div class="event ui segment">
							<div class="label">
								<img style="border-radius:10%;" src="<?= $value['path'] ?>">
							</div>
							<div class="content">
								<div class="summary">
								<a><?= $value['u_fullname'] ?></a> Posted
									<div class="date">
										<?= $value['date'] ?>
									</div>
								</div>
								<div class="extra text">
									<?= $value['content'] ?>
								</div>
								<div class="meta">
									<a class="like">
										<i class="like icon"></i> 5 Likes
									</a>
								</div>
							</div>
						</div>
						<?php break;

						case 'cmt': ?>
							<div class="event">
								<div class="label">
									<img style="border-radius:10%;" src="<?= $value['path'] ?>">
								</div>			
								<div class="content">
									<div class="summary">
									<a><?= $value['firstName'] ?></a> Commented on
										<div class="date">
											<?= $value['date'] ?>
										</div>
									</div>
									<div class="extra text">
										<?= $value['content'] ?>
									</div>
									<div class="meta">
										<a class="like">
											<i class="like icon"></i> 5 Likes
										</a>
									</div>
								</div>
							</div>
						<?php break;

						case 'qs':
							//echo "Question: {$value['id']} - {$value['content']} @ {$value['date']} <br><br>";
							break;

						case 'fl': ?>
							<div class="event">
								<div class="label">
									<img style="border-radius:10%;" src="<?= $value['path'] ?>">
								</div>
								<div class="content">
									<div class="summary">
										<a class="user">
											<?= $value['firstName'] ?>
										</a> Followed
										<div class="date">
											<?= $valie['date'] ?>
										</div>
									</div>
								</div>
							</div>
						<?php break;

						case 'ps': ?>
							<div class="event">
								<div class="label">
									<i class="heart red icon"></i>
								</div>
								<div class="content">
									<div class="summary">
										<?= $value['firstName'] ?> Liked a <a href="<?= $value['id'] ?>">post</a>.
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