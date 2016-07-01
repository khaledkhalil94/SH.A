<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/classes/init.php");


$session->is_logged_in() ? require(ROOT_PATH . "questions/templates/question_self.php") : require(ROOT_PATH . "questions/templates/question_pub.php");