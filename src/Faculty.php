<?php  // TO BE REMOVED
// require_once('init.php');
// /**
// * 
// */
// class Faculty extends User {
// 	public $id, $section, $type, $title, $content, $created, $last_modified, $author, $status="1";
// 	protected static $table_name="content";
// 	protected static $db_fields = array();

// 	public function __construct(){
// 		global $db_fields;
// 		self::$db_fields = array_keys((array)$this);
// 	}

// 	public static function get_article($id){
// 		global $connection;
// 		$sql = "SELECT faculties.name AS fac_name, content.* FROM `content` 
// 				LEFT JOIN faculties ON content.section = faculties.id
// 				WHERE content.id = '{$id}'";
// 		$stmt = $connection->query($sql);
// 		return $stmt->fetchAll(PDO::FETCH_OBJ)[0];
// 	}

// 	public static function get_content($type, $id="", $limit=""){
// 		global $connection;
// 		$id = (int)$id;
// 		$sql = "SELECT faculties.name AS fac_name, content.* FROM `content` 
// 				LEFT JOIN faculties ON content.section = faculties.id
// 				WHERE content.type = '{$type}' ";
// 				if (!empty($id) || $id === "0") $sql .= "AND section = {$id} "; 
// 		$sql .= "AND status = 1 
// 				ORDER BY created DESC ";
// 				if (!empty($limit)) $sql .= "LIMIT {$limit} "; 
				
// 		$stmt = $connection->query($sql);
// 		return $stmt->fetchAll(PDO::FETCH_OBJ);
// 	}

// 	public static function main_content(){
// 		global $connection;
// 		$sql = "SELECT * FROM `content` 
// 				WHERE type = 'main'
// 				AND section = 0";
				
// 		$stmt = $connection->query($sql);
// 		return $stmt->fetchAll(PDO::FETCH_OBJ)[0];
// 	}

// 	public static function sidebar_content($id, $fac_id="", $limit=""){
// 		$articles = self::get_content("article", $fac_id, $limit);
// 		$var = "";
// 		foreach ($articles as $article): 
// 			if ($article->id != $id): 
// 				$var .= "<a href=\"articles.php?id={$article->id}\"><p>{$article->title}</p></a>";
// 			endif; 
// 		endforeach;
// 		return $var;
// 	}

// 	public static function sidebar_news($id="", $limit=""){
// 		$news = self::get_content("news","", $limit);
// 		foreach ($news as $new): 
// 			if ($new->id != $id): 
// 				echo "<a href=\"articles.php?id={$new->id}\"><p>{$new->title}</p></a>";
// 			endif; 
// 		endforeach;
// 	}

// 	public static function articles_count(){
// 		global $connection;
// 		$sql = "SELECT count(*) FROM `content`";
// 		return $connection->query($sql)->fetch()[0];
// 	}

// 	public static function pub_count(){
// 		global $connection;
// 		$sql = "SELECT count(*) FROM `content` WHERE status = 1";
// 		return $connection->query($sql)->fetch()[0];
// 	}

// 	// public static function get_count($type, $msql=""){
// 	// 	global $connection;
// 	// 	$sql = "SELECT count(*) FROM ".static::$table_name;
// 	// 	if(!empty($msql)) $sql .= $msql;
// 	// 	$res = $connection->query($sql);
// 	// 	return $res->fetch()[0];

// 	// }

// }
// $faculty = new Faculty();
 ?>