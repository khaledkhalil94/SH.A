<?php 
require_once('classes/init.php');

echo "<pre>";
print_r($_SESSION);
unset($_SESSION['fail']);

echo "<pre>";

$data = $connection->query("SELECT props FROM `students` WHERE id = 5501");
$data = $data->fetch()['props'];
$data = json_decode($data, true);
$data['has_pic'] = 0;

$json = json_encode($data);
$sql = "UPDATE `students` SET props = '$json' WHERE id = 5501";
$res = $connection->query($sql);
if(!$res){
	echo $connection->errorInfo()[2];
}
?>

<!-- {"faculty_id":"1","has_pic":"1","acc_vis":"1"} -->