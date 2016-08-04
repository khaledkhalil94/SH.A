<?php 
require_once('classes/init.php');

echo "<pre>";
print_r($_SESSION);
unset($_SESSION['fail']);
echo "</pre>";

?>
