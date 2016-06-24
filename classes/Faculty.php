<?php 
require_once('init.php');
/**
* 
*/
class Faculty extends User {
	public $id, $faculty_id, $type, $title, $content, $created, $last_modified, $author, $status;
	protected static $table_name="content";
	protected static $db_fields = array();

	public function __construct(){
		global $db_fields;
		self::$db_fields = array_keys((array)$this);
	}

	public static function display_content($type, $id){
		$sql = "SELECT * FROM `content` WHERE type = '{$type}' AND faculty_id = {$id} AND status = 1 ORDER BY created DESC";
		return parent::find_by_sql($sql);
	}

	public static function get_all_content($type){
		$sql = "SELECT * FROM `content` WHERE type = '{$type}' AND status = 1 ORDER BY created DESC";
		return parent::find_by_sql($sql);
	}

	public function updateContent($data){
		$this->update($data);
	}

}

 ?>