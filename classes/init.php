<?php 
defined('DB_HOST')   ? null : define('DB_HOST', "localhost");
defined('DB_USER')   ? null : define('DB_USER', "root");
defined('DB_PASS')   ? null : define('DB_PASS', "");
defined('DB_NAME')   ? null : define('DB_NAME', "sha2");

defined('BASE_URL')  ? null : define('BASE_URL', "/sha/");
defined('ROOT_PATH') ? null : define('ROOT_PATH',  $_SERVER["DOCUMENT_ROOT"] . "/sha/");
defined('DOCROOT')   ? null : define('DOCROOT', dirname(__FILE__));
defined('DEF_PIC')   ? null : define('DEF_PIC', ROOT_PATH."images/profilepic/pp.png");


defined('MAX_PIC_SIZE') ? null : define('MAX_PIC_SIZE', 300000);

require_once('Database.php');
require_once("Session.php");
require_once("User.php");
require_once('ProfilePicture.php');
require_once('pagination.php');
require_once('StudentInfo.php');
require_once('functions.php');

 function autoloader($class_name){
 	$path = DOCROOT . "/{$class_name}.php";
 	require_once($path);
 }

spl_autoload_register('autoloader');


if (defined('BASE_URL') && defined('USER_ID')) {
	define('USER_URL', BASE_URL."students/".USER_ID."/");
}



?>