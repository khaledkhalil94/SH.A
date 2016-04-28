<?php 
require_once('init.php');
class Database {

	protected static $db_fields = array('firstName', 'lastName', 'id');

	function __construct() {
		global $connection;
		$dsn = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST;
		try {
			$connection = new PDO($dsn,DB_USER,'');
			//echo "Connected to database.<br>";
		} catch (PDOException $e) {
			die($e->getMessage());
		}
	}

	
}

$database = new Database();

?>