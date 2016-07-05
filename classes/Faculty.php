<?php  // TO BE REMOVED
require_once('init.php');
/**
* 
*/
class Faculty extends User {
	public $id, $faculty_id, $type, $title, $content, $created, $last_modified, $author, $status="1";
	protected static $table_name="content";
	protected static $db_fields = array();

	public function __construct(){
		global $db_fields;
		self::$db_fields = array_keys((array)$this);
	}

	public static function get_content($type, $id=""){
		global $connection;
		$sql = "SELECT * FROM `content` 
				WHERE type = '{$type}' ";
				if (!empty($id)) $sql .= "AND faculty_id = {$id} "; 
		$sql .= "AND status = 1 
				ORDER BY created DESC
				";
		$stmt = $connection->query($sql);
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public static function main_content(){
		 $content = self::get_content("main", 0);
		 $main = $content[0];
		 $main = $main->content;
		 return $main;
	}

	public static function sidebar_content($type, $id){
		$article = self::find_by_id($id);
		$articles = self::get_content($type, $article->faculty_id);
		foreach ($articles as $article): 
			if ($article->id != $id): 
				echo "<a href=\"articles.php?id={$article->id}\"><p>{$article->title}</p></a>";
			endif; 
		endforeach;
	}

	public static function sidebar_news(){
		$news = self::get_content("news");
		foreach ($news as $new): 
			echo "<a href=\"articles.php?id={$new->id}\"><p>{$new->title}</p></a>";
		endforeach;
	}

	public static function articles_count(){
		global $connection;
		$sql = "SELECT count(*) FROM `content`";
		return $connection->query($sql)->fetch()[0];
	}

	public static function pub_count(){
		global $connection;
		$sql = "SELECT count(*) FROM `content` WHERE status = 1";
		return $connection->query($sql)->fetch()[0];
	}

	// public static function get_count($type, $msql=""){
	// 	global $connection;
	// 	$sql = "SELECT count(*) FROM ".static::$table_name;
	// 	if(!empty($msql)) $sql .= $msql;
	// 	$res = $connection->query($sql);
	// 	return $res->fetch()[0];

	// }

}
$faculty = new Faculty();
 ?>