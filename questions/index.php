<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/src/init.php");
$pageTitle = "Students";
$sec = "questions";
include (ROOT_PATH . 'inc/head.php');
$sec = isset($_GET['section']) ? $_GET['section'] : "";

$sections = $QNA->get_sections();

foreach ($sections as $s) {
	switch ($s['acronym']) {
		case $sec:
			$qs = $QNA->get_questions($s['id']);
			$sec_name = $s['title'];
			break 2; // breaks out of the two casses (foreach and switch)
		
		default:
			$qs = $QNA->get_questions();
			$sec_name = 'All';
			break;
	}
}

?>
<body>
	<div class="container section">
		<div class="ui large search" id="question-search">
			<div class="ui icon input">
				<input type="text" placeholder="Search WILL ADD LATER">
				<i class="search icon"></i>
			</div>
			<div class="results"></div>
		</div>
		
			<div class="questions sortby">
				<p style="display:inline;">Show questions from: </p>
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
					<script>$('.ui.dropdown').dropdown();</script>
			</div>


		<?php if ($session->is_logged_in()): ?>
			<a type="button" href="./new" class="ui green button">Ask a new question</a>
		<?php endif; ?>
		<?= msgs(); ?>
		<hr>
		<div class="container questions" id="questions">
			<h3>Questions</h3>
			<!-- TODO: Add pagination -->
			<?php 
			if (count($qs) < 1) { echo "There are no questions in this section yet.<br>"; 
			} else {
				foreach ($qs as $q):

					$user = User::get_user_info($q->uid);
					if(!$user) continue;
					if(($q->status != 1) && ($q->uid != USER_ID)) continue;
					$self = $q->uid === USER_ID ?: false;
					$commentsCount = count(Comment::get_comments($q->id));
					$votes = QNA::get_votes($q->id);
					$reports_count = QNA::get_reports("questions", $q->id) ? count(QNA::get_reports("questions", $q->id)) : null;
					$img_path = $user->img_path ?: DEF_PIC;
					?>
				 	<div class="ui items">
				 		<div class="item">
				 			<div class="ui tiny image">
				 				<a href="/sha/user/<?= $user->id; ?>/"><img src="<?= $img_path; ?>"></a>
				 			</div>
				 			<div class="content">
				 				<a href="../questions/question.php?id=<?= $q->id; ?>"><h3> <?= $q->title; ?> </h3></a>
				 				<div class="meta">
				 					<span style="display:inline;" class="price">Asked by <a class="user-title" user-id="<?= $user->id; ?>" href="/sha/user/<?= $user->id; ?>/"><?=$user->full_name;?></a></span>
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
		 	}?>
		 </div>
 	</div>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>
