<?php 
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/src/init.php");

$QNA = new QNA();
$sections = $QNA->get_sections();

$cp = isset($_GET['page']) ? $_GET['page'] : 1;
$rpp = 4;

if(isset($_GET['section']) && !empty($_GET['section'])){
	$sec = $_GET['section'];
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
				break;
		}
	}
} else {

	$qs = $QNA->get_questions();

	$count = count($qs);

	$pag = new Pagination($count, $cp, $rpp);

	$offset = $pag->offset();


	$qs = $QNA->get_questions($rpp, $offset);
	$sec_name = 'All';
}

foreach ($qs as $q):
	if(($q->status != 1) && ($q->uid != USER_ID)) continue;
	$self = $q->uid === USER_ID ?: false;
	$commentsCount = count(Comment::get_comments($q->id));
	$votes = QNA::get_votes($q->id);
	$votes = $votes ?: "0";
	$reports_count = QNA::get_reports("questions", $q->id) ? count(QNA::get_reports("questions", $q->id)) : null;
	$img_path = $q->img_path ?: DEF_PIC;
	?>
 	<div class="ui items">
 		<div class="item">
 			<div class="ui tiny image">
 				<a href="/sha/user/<?= $q->uid; ?>/"><img src="<?= $img_path; ?>"></a>
 			</div>
 			<div class="content">
 				<a href="../questions/question.php?id=<?= $q->id; ?>"><h3> <?= $q->title; ?> </h3></a>
 				<div class="meta">
 					<span style="display:inline;" class="price">Asked by <a class="user-title" user-id="<?= $q->uid; ?>" href="/sha/user/<?= $q->uid; ?>/"><?=$q->full_name;?></a></span>
 					<span title="<?= $q->created; ?>" id="post-date" class="time"><?= $q->created; ?></span>
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
 ?>