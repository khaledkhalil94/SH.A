<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/init.php");

$QNA = new QNA();
$sections = $QNA->get_sections();

$cp = isset($_GET['page']) ? $_GET['page'] : 1;
$rpp = 6;

$sec = isset($_GET['section']) ? $_GET['section'] : NULL;

foreach ($sections as $s) {
	switch ($s['acronym']) {
		case $sec:
			$sec_name = $s['title'];

			$QNA->section = $s['id'];

			$qs = $QNA->get_questions();

			$count = count($qs);

			$pag = new Pagination($count, $cp, $rpp);

			$offset = $pag->offset();

			$qs = $QNA->get_questions($rpp, $offset);

			break 2; // breaks out of the two casses (foreach and switch)

		default:
			$qs = $QNA->get_questions();

			$count = count($qs);

			$pag = new Pagination($count, $cp, $rpp);

			$offset = $pag->offset();

			$qs = $QNA->get_questions($rpp, $offset);
			$sec_name = 'All';
			break;
	}
}
$pageTitle = "Stories";
$sec = "questions";
include (ROOT_PATH . 'inc/head.php');
?>
<body>
	<div class="ui container section">
		<div class="ui large search" id="ent">
			<div class="ui icon input">
				<input type="text" autocomplete="off" id="sec_ent">
			</div>
			<div class="results"></div>
		</div>

			<div class="questions sortby">
				<p style="display:inline;">Show stories from: </p>
				<div class="ui inline dropdown">
					<div class="text">
					<?= $sec_name ?>
					</div>
					<i class="dropdown icon"></i>
					<div class="menu">
						<a href=".">
							<div class="item <?= $sec == "" ? "active selected" : null; ?>">
							All
							</div>
						</a>
					<?php foreach($sections AS $section): ?>
						<a href="?section=<?= $section['acronym'] ?>">
							<div class="item <?= $section['acronym'] == $sec ? "active selected" : null; ?>">
							<?= $section['title']; ?>
							</div>
						</a>
					<?php endforeach; ?>
					</div>
				</div>
				<br>
				<br>
			</div>
		<?php if ($session->is_logged_in()): ?>
			<a type="button" href="new/create" class="ui green button">Write a new story</a>
			<a type="button" href="new/random" data-inverted data-tooltip="Create a new story with generated content" class="ui orange button">Generate story</a>
		<?php endif; ?>
		<?= msgs(); ?>
		<br><br><hr>
		<h3>Stories</h3>
		<?= $pag->display(); ?>
		<div class="questions front-page" id="questions">
			<?php
			if (count($qs) < 1) { echo "There are no stories in this section yet.<br>";
			} else {
				foreach ($qs as $q):
					if(($q->status != 1) && ($q->uid != USER_ID)) continue;
					$self = $q->uid === USER_ID ?: false;
					$commentsCount = count(Comment::get_comments($q->id));
					$votes = QNA::get_votes($q->id);
					$votes = $votes ?: "0";
					$reports_count = QNA::get_reports_count($q->id) ?: null;
					$img_path = $q->img_path ?: DEF_PIC;
					$type = $q->fid == 5 ? 'Generated' : 'Written';
					?>
				 	<div class="ui items">
				 		<div class="item">
				 			<div class="ui tiny image">
				 				<a href="/user/<?= $q->uid; ?>/"><img src="<?= $img_path; ?>"></a>
				 			</div>
				 			<div class="content">
				 				<a href="../questions/question.php?id=<?= $q->id; ?>"><h3> <?= $q->title; ?> </h3></a>
				 				<div class="meta">
				 					<span style="display:inline;" class="price"><?= $type ?> by <a class="user-title" user-id="<?= $q->uid; ?>" href="/user/<?= $q->uid; ?>/"><?=$q->full_name;?></a></span>
				 					<span title="<?= $q->created." GMT".Date('P') ?>" class="time datetime"><?= $q->created; ?></span>
				 				</div>
				 				<br />
				 				<div class="extra">
				 					<span class="likes" title="Likes" style="font-size:medium;">
					 					<i class="mdi mdi-heart"></i>
					 					<?= $votes; ?>
				 					</span>
				 					<span class="comments" title="Comments">
					 					<a href="./question.php?id=<?= $q->id; ?>">
						 					<i class="mdi mdi-comment-multiple-outline"></i>
						 					<?= $commentsCount; ?>
					 					</a>
				 					</span>
				 					<?php if($session->adminCheck() && $reports_count >= 1){ ?>
				 					<span class="reports" title="Reports" style="font-size:medium;">
					 					<a href="./question.php?id=<?= $q->id; ?>">
						 					<i class="mdi mdi-flag"></i>
						 					<?= $reports_count; ?>
					 					</a>
				 					</span>
				 					<?php } ?>
				 				</div>
				 			</div>
				 		</div>
				 	</div>
					<?php
			 	endforeach;
		 	}?>
		 </div><br>
		<?= $pag->display(); ?>
 	</div>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
<script src='/scripts/q_pag.js'></script>
</html>