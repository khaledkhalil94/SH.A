<?php 

defined('DB_HOST')   ? null : define('DB_HOST', "localhost");
defined('DB_USER')   ? null : define('DB_USER', "root");
defined('DB_PASS')   ? null : define('DB_PASS', "");
defined('DB_NAME')   ? null : define('DB_NAME', "sha2");
define("BASE_URL", "/sha/");
define("ROOT_PATH", $_SERVER["DOCUMENT_ROOT"] . "/sha/");
define('DOCROOT', dirname(__FILE__));


require_once("Session.php");
function autoloader($class_name){
	$path = DOCROOT . "/{$class_name}.php";
	require_once($path);
}

spl_autoload_register('autoloader');
	


?>