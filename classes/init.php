<?php 
defined('DB_HOST')   ? null : define('DB_HOST', "localhost");
defined('DB_USER')   ? null : define('DB_USER', "root");
defined('DB_PASS')   ? null : define('DB_PASS', "");
defined('DB_NAME')   ? null : define('DB_NAME', "sha2");

defined('BASE_URL')  ? null : define('BASE_URL', "/sha/");
defined('ROOT_PATH') ? null : define('ROOT_PATH',  $_SERVER["DOCUMENT_ROOT"] . "/sha/");
defined('DOCROOT')   ? null : define('DOCROOT', __DIR__);
defined('DEF_PIC')   ? null : define('DEF_PIC', BASE_URL."images/profilepic/pp.png");


defined('MAX_PIC_SIZE') ? null : define('MAX_PIC_SIZE', 300000);

require_once(__DIR__.'/Database.php');
require_once(__DIR__."/Session.php");
require_once(__DIR__."/User.php");
require_once(__DIR__.'/ProfilePicture.php');
require_once(__DIR__.'/pagination.php');
require_once(__DIR__.'/StudentInfo.php');
require_once(__DIR__.'/functions.php');
require_once(__DIR__.'/Faculty.php');
require_once(__DIR__.'/Comment.php');
require_once(__DIR__.'/QNA.php');

 function autoloader($class_name){
 	$path = DOCROOT . "/{$class_name}.php";
 	require_once($path);
 }

spl_autoload_register('autoloader');


if (defined('BASE_URL') && defined('USER_ID')) {
	define('USER_URL', BASE_URL."students/".USER_ID."/");
}


$faculties = array("Engineering" => 1, "Computer Science" => 2, "Medicine" => 3);

	$greenIcon = "<i style=\"color:green;\" class=\"fa fa-circle status-published\"></i>";
	$greyIcon = "<i style=\"color:grey;\" class=\"fa fa-circle status-published\"></i>";
	$redIcon = "<i style=\"color:red;\" class=\"fa fa-circle status-published\"></i>";
?>