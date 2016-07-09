<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");
$pageTitle = "Admin Control Panel";
$session->adminLock();
if (isset($_GET['dl'])) {
	if(QNA::delete_report($_GET['dl'])) $session->message("Report has been deleted.", "reports.php", "success");
}
?>
<body> TO BE REMOVED
	<?php
	include (ROOT_PATH . 'inc/head.php'); 
	?>

	<div class="main">
		<div class="container section">
			<div class="wrapper">
			<h2>Comments reports</h2>
			</div>
			<?php $reports = QNA::reports("comments"); // *array* id's of the reported posts 
			//print_r($reports); exit;
			?>

			<table class="table table-hover">
				<thead>
					<tr>
						<th>Comment</th>
					</tr>
				</thead>
				<tbody>
					<?php
					//print_r(QNA::get_reports("comments")); exit;
					$i = 0;
				foreach ($reports as $report):
					$i++;
					$reporters = QNA::get_reports("comments", $report);
					$reportz = array_shift(QNA::get_reports("comments", $report)); // gets the reported comment as an object
			//print_r($reporters); exit;
					?>
					<tr>
						<td>
							<a style="color:black;text-decoration: none;" href="/sha/questions/question.php?id=<?= $reportz->post_id; ?>">
								<h3><p style="list-style:none;"><?= $reportz->content; ?></a> <span style="font-size:small;background-color:red;">Reported <?= $reportz->count; ?> times</span></p></h3>
							</a>
							<p style="list-style:none;">by: <?= $reportz->uid; ?></p>
								<button type="button" class="btn btn-info" data-toggle="collapse" data-target="#<?= $i; ?>">View report details</button>
								<div id="<?= $i; ?>" class="collapse"><br>
								<?php foreach ($reporters as $report) { ?>
									<p>"<?= $report->content; ?>"     <span style="float:right;"> Reported by: <?= $report->reporterName." - ".get_timeago($report->date); ?> <a type="button" href="?dl=<?=$report->id;?>" class="btn btn-danger">Delete</a></span></p>
									<hr>
								<?php } ?>
								</div>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table><br><hr>
		<?= BackBtn(); ?>
		</div>
	</div>
</div>

<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>