<?php 
defined('DB_HOST')   ? null : define('DB_HOST', "localhost");
defined('DB_USER')   ? null : define('DB_USER', "root");
defined('DB_PASS')   ? null : define('DB_PASS', "");
defined('DB_NAME')   ? null : define('DB_NAME', "sha2");
define("BASE_URL", "/sha/");
define("ROOT_PATH", $_SERVER["DOCUMENT_ROOT"] . "/sha/");
define('DOCROOT', dirname(__FILE__));
define('DEF_PIC', "C:/xampp/htdocs/sha/images/profilepic/pp.png");


require_once("Session.php");
require_once("User.php");
require_once('Database.php');
require_once('ProfilePicture.php');
require_once('pagination.php');

 function autoloader($class_name){
 	$path = DOCROOT . "/{$class_name}.php";
 	require_once($path);
 }

 function redirect_to_D($location = NULL, $delay="0") {
  if ($location != NULL) {
    header("refresh:{$delay};url={$location}");
    exit;
  }
}

 spl_autoload_register('autoloader');

if(isset($session->user_id)){
	define("USER_ID", $session->user_id);
}

?>