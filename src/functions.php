<?php
require_once('init.php');

function msgs(){
	global $session;
  	$msg = $session->displayMsg();
	$output = "";
	$output .= "<div class=\"alert alert-{$msg["msgType"]} alert-dismissible\" role=\"{$msg["msgType"]}\">";
  	$output .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
  	$output .= $msg["msg"];
	$output .= "</div>";

	if(!empty($session->msg)){
		return $output;
	}
}

// converts an integer to a readable file size
// source: SO
function human_filesize($bytes, $decimals = 2) {
	$sz = 'BKMGTP';
	$factor = floor((strlen($bytes) - 1) / 3);
	return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . $sz[(int)$factor];
}

// TBR
function get_timeago( $ptime ){

	$etime = time() - strtotime($ptime);
	if ($etime < 1)
	{
		return '0 seconds';
	}

	$a = array( 365 * 24 * 60 * 60  =>  'year',
		30 * 24 * 60 * 60  =>  'month',
		24 * 60 * 60  =>  'day',
		60 * 60  =>  'hour',
		60  =>  'minute',
		1  =>  'second'
		);
	$a_plural = array( 'year'   => 'years',
		'month'  => 'months',
		'day'    => 'days',
		'hour'   => 'hours',
		'minute' => 'minutes',
		'second' => 'seconds'
		);

	foreach ($a as $secs => $str)
	{
		$d = $etime / $secs;
		if ($d >= 1)
		{
			$r = round($d);
			return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
		}
	}
}

function displayDate($dat, $format=""){
	$date = strtotime($dat);
	if(empty($format)){
		$date = (date('Y',$date)) == date('Y') ? date('j M h:i',$date) : date('j M Y, h:i',$date);
	} else {
		$date = date($format,$date);
	}
	return $date;
}

function ctrim($content, $lenght, $dots=false, $link=''){
	if (strlen($content) > $lenght) {
		$content = substr($content, 0, $lenght);
		if($dots === true){
			$content .= !empty($link) ? "... <a href='{$link}'>Read More</a>" : "...";
		}
	}
	return $content;
}

function append_queries($query){
	if(empty($query)) return false; // if the url does not have a query string

	$query = array_unique(explode("&",$query)); // skip any duplicated queries

	foreach ($query as $q) {
		list($k, $v) = explode("=", $q);
		$qs[$k] = $v;
	}
	foreach ($qs as $key => $value) {
		$$key = $value;

	}
	$qz = array();
	if (count($qs) > 1) {
		$qz['display'] = "?sortby={$sortby}&";
		$qz['sortby'] = "?display={$display}&";
	} elseif(isset($display)){
		$qz['display'] = "?";
		$qz['sortby'] = "?display={$display}&";
	} elseif(isset($sortby)) {
		$qz['sortby'] = "?";
		$qz['display'] = "?sortby={$sortby}&";
	}

return $qz;
}

function BackBtn(){
	echo "<a type=\"button\" href=\".\" class=\"btn btn-default\">Back</a>";
}

function sanitize_id($id){
	return (int)filter_var($id, FILTER_SANITIZE_NUMBER_INT);
}

// deletes a directory with files and folders inside
// source: SO
function rm_dir($dir) {
    foreach(scandir($dir) as $file) {
        if ('.' === $file || '..' === $file) continue;
        if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
        else unlink("$dir/$file");
    }

    return rmdir($dir) ?: false;
}


function printX($array){
	echo "<pre>";
	print_r($array);
	echo "</pre>";
	exit;
}

function date_compare($a, $b){
	 $t1 = strtotime($a['date']);
	 $t2 = strtotime($b['date']);
	 return $t1 - $t2;
}

// return the current datetime with a default format 2016-10-25 10:03:36 (sql datetime format)
function getNow($format=null){

	return $format ? Date($format) : Date('Y-m-d H:i:s');
}
?>