<?php
require_once ("classes/init.php");

echo "Logged in: "; echo $session->is_logged_in() ? "true" : "false";
      echo "<pre>";
      print_r($_SESSION);
      echo "<br>";
		echo $session->username;
