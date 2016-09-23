<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/src/init.php");
$pageTitle = "Admin Control Panel";
$session->adminLock();

if(isset($_POST['rp_rm']) && $_POST['rp_rm'] == 'true'){

	$id = $_POST['id'];
	Admin::removeReport($id);
	echo "1";
	exit;
}

$id = $_GET['id'];

$type = Post::PorQ($id);

if(!$type) Redirect::redirectTo('404');

if($type == 'q'){

	$post = QNA::get_question($id);
	$reps = QNA::get_reports($id);
} else {

	$post = (object) Comment::getComment($id);
	$reps = Comment::get_reports($id);
}


$sec = "staff";

include (ROOT_PATH . 'inc/head.php');
?>

<body>
	<div class="main" id="admincp">
		<div class="ui container section rep_mng">
			<?php if($type == 'q'){ ?>
			<div class="question report">
				<div class="ui segment">
					<div class="ui items">
						<div class="item">
							<div class="content">
								<a href="<?= View::qsn($id) ?>" class="header"><?= $post->title; ?></a>
								<div class="details">
									<p class="time">Asked by <?= View::user($post->uid) ?> - <?= View::postDate($id)?></p>
								</div><hr>
								<div class="description">
									<p><?= $post->content; ?></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php } else { ?>
			<div class="comment report">
				<div class="ui segment">
					<div class="ui items">
						<div class="item">
							<div class="content">
								<div class="details">
									<p class="time">Comment by <?= View::user($post->uid) ?> - <?= View::postDate($id)?></p>
								</div><hr>
								<div class="description">
									<p><?= $post->content; ?></p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
				<h2>Reports</h2>
				<table class="ui red table">
					<thead>
						<tr>
							<th>Reported By</th>
							<th>Report Message</th>
							<th>Date</th>
							<th class="center aligned">Remove</th>
						</tr>
					</thead>
					<?php if(empty($reps)){ ?>
					<tbody>
						<tr>
							<td>-</td>
							<td>-</td>
							<td>-</td>
							<td class="center aligned">-</td>
						</tr>
					</tbody>
					<?php } else { ?>
					<tbody>
						<?php foreach($reps as $rp): ?>
						<tr id="<?= $rp->rp_id ?>">
							<td><?= View::user($rp->reporter); ?></td>
							<td><p><?= $rp->r_content ?></p></td>
							<td><?= get_timeago($rp->rp_date) ?></td>
							<td class="center aligned"><i class="ui remove icon"></i></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
					<?php } ?>
				</table>
			</div>
		</div>
	</div>
</div>
<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>
<script>
$('.ui.remove.icon').click(function(){
	_this = $(this);
	id = _this.closest('tr').attr('id');
	$.post("report.php", { 'rp_rm': "true", 'id': id},function(data){
		if(data == '1'){
			_this.closest('tr').remove();
		}
	}, 'json')
});
</script>