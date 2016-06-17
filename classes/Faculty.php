<?php 
require_once('init.php');
/**
* 
*/
class Faculty extends User {
	public $id, $name,	$title,	$content;
	protected static $table_name="faculties";
	protected static $db_fields = array();

	public function __construct(){
		global $db_fields;
		self::$db_fields = array_keys((array)$this);
	}

	public static function display($content, $id){
		global $connection;
		$sql = "SELECT {$content} FROM `faculties` WHERE id = {$id}";
		$stmt = $connection->query($sql);
		$data = $stmt->fetch(PDO::FETCH_OBJ);
		return $data->$content;
	}

	public function updateContent($data){
		$this->update($data);
	}


}

 ?>