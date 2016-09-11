 <?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/src/init.php");
$pageTitle = "Admin Control Panel";
$session->adminLock();
$display = isset($_GET['display']) ? $_GET['display'] : "";
$sortby = isset($_GET['sortby']) ? $_GET['sortby'] : null;

switch ($display) {
	case 'pri':
		$qs = Admin::getQuestions(0,$sortby); 
		$Count = count($qs);
		break;
	case 'pub':
		$qs = Admin::getQuestions(1,$sortby); 
		$Count = count($qs);
		break;
	case 'rep':
		require("index_rep.php");
		exit;
		break;
	default:
		$qs = Admin::getQuestions("",$sortby); 
		$Count = count($qs);
		break;
}

$query = append_queries($_SERVER['QUERY_STRING']);

?>
<body>
	<?php 
	include (ROOT_PATH . 'inc/head.php');
	?>

	<div class="main">
		<div class="ui container section">
			<div class="wrapper">
			<?= msgs(); ?>
			<p>TODO: search</p>
			<h2 >QnA</h2>
			</div>
			<div class="sortby">
			<p>Display:</p>
				<nav>
					<ul class="pager">
						<li><a href=".">All</a></li>
						<li><a href="<?= $query['display'] ?: "?"; ?>display=pub"><?= $greenIcon; ?> Public</a></li>
						<li><a href="<?= $query['display'] ?: "?"; ?>display=pri"><?= $greyIcon; ?> Private</a></li>
						<li><a href="<?= $query['display'] ?: "?"; ?>display=rep"><?= $redIcon; ?> Reported</a></li>
					</ul>
				</nav>
			</div>
			<div class="sortby">
			<p>Sort by:</p>
				<nav>
					<ul class="pager">
						<li><a href="<?= $query['sortby'] ?: "?"; ?>sortby=date">Date</a></li>
						<li><a href="<?= $query['sortby'] ?: "?"; ?>sortby=status">Status</a></li>
						<li><a href="<?= $query['sortby'] ?: "?"; ?>sortby=author">Author</a></li>
					</ul>
				</nav>
			</div>
			<hr>
			
			<table class="table table-hover">
				<thead>
					<tr>
						<th class="col-md-7">Articles (<?= $Count; ?>)</th>
						<th class="col-md-2">Status</th>
						<th class="col-md-1">Reports</th>
						<th class="col-md-1">Edit</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($qs as $q):
					//$q = array_shift($q);
					//print_r($q); exit;
						$id = $q->id; $uid= $q->uid; $title = $q->title; $content = $q->content;
						$created = $q->created; $last_modified = $q->last_modified; $status = $q->status;
						$reports_count = QNA::get_reports("questions", $id) ? QNA::get_reports("questions", $id)[0]->count : null;
					?>
					<tr>
						<td >
							<p><a href="/sha/questions/question.php?id=<?= $id; ?>"><b><?= $title; ?></b></a></p>
							<div class="time"><?= displayDate($created); ?></div>
							<p><?= $content; ?></p>
						</td>
						<td>
							<p"><?php if($status == "1"){echo $greenIcon." Public";}else{echo $redIcon." Private";}?></p>
						</td>
						<?php if(empty($reports_count)){ ?>
							<td><span><?= $reports_count; ?></span></td>
						<?php } else { ?>
							<td><a href="report.php?id=<?=$q->id;?>"><?= $reports_count; ?></a></td>
						<?php } ?>
						<td><a type="button" href="edit.php?id=<?= $id; ?>" class="btn btn-warning">Edit</a></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php 
include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>

<table>
	<thead>
	<tr>
		<th></th>
	</tr>
	</thead>
	<tbody>
		<tr>
			<td>
			
			</td>
		</tr>
	</tbody>
</table>