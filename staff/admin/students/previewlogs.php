<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");
$session->adminLock();
$msg = $session->displayMsg();
$id = isset($_GET['id']) ? $_GET['id'] : null;
if(!$id){
	redirect_to_D("/sha", 2);
}
$User = StudentInfo::find_by_id($id) ? StudentInfo::find_by_id($id) : StaffInfo::find_by_id($id);
$student = Student::find_by_id($User->id);

if (isset($_GET["clall"]) && $_GET["clall"] == "1") {
	Admin::deletelogs($id);
	$session->message("All logs have been deleted.");
	redirect_to_D(basename(__FILE__)."?id={$id}");
} elseif (isset($_GET["dellog"])) {
	Admin::deletelog($_GET["dellog"]);
	redirect_to_D(basename(__FILE__)."?id={$id}");
}

$pageTitle = "Preview Logs";
include (ROOT_PATH . "inc/head.php");
 ?>

<div class="container">
	<div class="jumbotron">
	 <div class="section">
		 <div class="pagination">
		  <?php $rpp = 6;
		  Pagination::display(Admin::getUserLogsCount($id), $rpp); ?>
		</div>
	</div>
	<br>
	<?php $logs = Admin::get_logs($id, $pagination->rpp, $pagination->offset()); 
	if(!empty($logs)){ ?>
		<a href="<?= "?id={$id}&clall=1"; ?>">Clear all logs</a>
	<?php
	}
		$output = "";
		echo "<ul class=\"logs\">";
		foreach ($logs as $log) {
			$output .= "<li>";
			$output .= "<div class=\"row\">";
			$output .= "<div class=\"time\">";
			$output .= $log['time'];
			$output .= "</div>";
			$output .= "<p>";
			$output .= "User ". $log['log'];
			$output .= "<a style=\"float:right;\" class=\"btn btn-default\" href=\"".basename(__FILE__)."?id={$id}&dellog=". $log['id'] ."\"><i class=\"fa fa-trash-o fa-lg\"></i> Delete</a>";
			$output .= "</p>";
			$output .= "</div>";
			$output .= "</li>";
		}
		echo empty($logs) ? empty($logs) && $msg ? $msg : "User has no logs." : $output;

		echo "</ul>";
	  ?>
	  <a class="btn btn-default" href="<?= "student.php?id=".$id; ?>" role="button">Go back</a>
	</div>
</div>

<?php
include (ROOT_PATH . 'inc/footer.php');
?>