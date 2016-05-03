<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");
$session->adminLock();
$id = isset($_GET['id']) ? $_GET['id'] : null;

if(!$id){
	redirect_to_D("/sha", 2);
}
$studentInfo = StudentInfo::find_by_id($id) ? StudentInfo::find_by_id($id) : die("User was not found");
$student = Student::find_by_id($studentInfo->id);

if (isset($_GET["clall"]) && $_GET["clall"] == "1") {
	Admin::deletelogs($id);
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
	<?php $logs = Admin::getUserLogs($id); 
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
			$output .= "</p>";
			$output .= "<br>";
			$output .= "<a href=\"".basename(__FILE__)."?id={$id}&dellog=". $log['id'] ."\">Delete log</a>";
			$output .= "</div>";
			$output .= "</li>";
		}
		echo empty($logs) ? "User has no logs." : $output;
		echo "</ul>";
	  ?>
	</div>
</div>

<?php
include (ROOT_PATH . 'inc/footer.php');
?>