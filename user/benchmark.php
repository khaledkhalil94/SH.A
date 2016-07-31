<?php 
//place this before any script you want to calculate time
$time_start = microtime(true); 

//sample script
for($i=0; $i<1000; $i++){
	require_once("students.php");
}

$time_end = microtime(true);

//dividing with 60 will give the execution time in minutes other wise seconds
$execution_time = ($time_end - $time_start);

//execution time of the script
echo '<b>Total Execution Time:</b> '.$execution_time.' Seconds';
 ?>