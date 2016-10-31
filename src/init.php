<?php
defined('DB_HOST')   ? null : define('DB_HOST', "localhost");
defined('DB_USER')   ? null : define('DB_USER', "root");
defined('DB_PASS')   ? null : define('DB_PASS', "");
defined('DB_NAME')   ? null : define('DB_NAME', "sha2");

defined('BASE_URL')  ? null : define('BASE_URL', "/sha/");
defined('ROOT_PATH') ? null : define('ROOT_PATH',  $_SERVER["DOCUMENT_ROOT"] . "/sha/");
defined('DOCROOT')   ? null : define('DOCROOT', __DIR__);
defined('DS')        ? NULL : define('DS', "/");


defined('DEF_IMG_UP_DIR')  ? null : define('DEF_IMG_UP_DIR', ROOT_PATH . 'photos/');

defined('DEF_PIC_PATH')   ? null : define('DEF_PIC_PATH',  BASE_URL."photos/");
defined('DEF_PIC')   ? null : define('DEF_PIC',  BASE_URL."images/dpp.png");

// image validation
// todo later: adjust these settings in the admin panel
$imgValidation = array(
	'allowed_ext_C' => array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG),
	'allowed_ext' => array('gif','jpg','jpeg','png'),
	'max_size' => 2097152, // 2097152 byte == 2mb
	'max_width' => 800,
	'max_height' => 800
	);

// reCaptcha secret key
define('RECAP_SKEY', '6LdZ8gcUAAAAAKNsM82igmt7nN_Xn-o55hJmEkkb');

// define table names
define('TABLE_COMMENTS', 'comments');
define('TABLE_USERS', 'students');
define('TABLE_PROFILE_PICS', 'profile_pic');
define('TABLE_POINTS', 'points');
define('TABLE_INFO', 'login_info');
define('TABLE_PRIVACY', 'user_privacy');
define('TABLE_QUESTIONS', 'questions');
define('TABLE_REPORTS', 'reports');
define('TABLE_MESSAGES', 'messages');
define('TABLE_SECTIONS', 'sections');
define('TABLE_FOLLOWING', 'following');
define('TABLE_BLOCKS', 'block_list');
define('TABLE_SAVED', 'saved');
define('TABLE_ACTIVITY', 'activity');

 function autoloader($class_name){
 	$path = DOCROOT . "/{$class_name}.php";
 	require_once($path);
 }

spl_autoload_register('autoloader');

require_once(__DIR__.'/functions.php');
require_once(__DIR__.'/Database.php');
require_once(__DIR__."/Session.php");

if($session->is_logged_in()) $msgCount = Messages::getMsgsCount();


if (defined('BASE_URL') && defined('USER_ID')) {
	define('USER_URL', BASE_URL."user/".USER_ID."/");
}

include(ROOT_PATH.'src/secret.php');
?>