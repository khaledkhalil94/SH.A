<?php 

try {
	$db = new PDO("mysql:host=localhost;dbname=sha;port=3306", "root");
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->exec("SET NAMES 'utf8'");
 
} catch (Exception $e) {
	echo "Could not connect to database!";
	exit;
}


?>