<?php 
require_once('classes/init.php');

echo "<pre>";
print_r($_SESSION);
unset($_SESSION['fail']);
echo "</pre>";

?>

<!-- {"faculty_id":"1","has_pic":"1","acc_vis":"1"} -->
