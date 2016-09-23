<?php 
require_once('init.php');
/**
* 
* handles all the actions for the question page, questions
*
*/
class QNA {

	public $PostID;

	public $section='';

	static $table = TABLE_QUESTIONS;

	public function __construct($PostID=null){
		$this->PostID = (int)$PostID;
	}

	/**
	 * create a new question
	 *
	 * @param array $data data
	 *
	 * @return int|string
	 */
	public function create($data){
		$database = new Database();

		//$data = ['title' => $title, 'content' => $content, 'section' => $section, 'uid' => USER_ID];
		$data['uid'] = USER_ID;

		$insert = $database->insert_data(TABLE_QUESTIONS, $data);

		if($insert === true){

			$PostID = $this->PostID = $database->lastId;

			return (int)$PostID;

		} else {

			return array_shift($database->errors)[2];
		}
	}

	/**
	 * get a question but it's id
	 *
	 * @param $PostID int
	 *
	 * @return mixed
	 */
	public function get_question($PostID){
		global $connection;

		$sql = "SELECT students.id AS uid, CONCAT(students.firstName, ' ', students.lastName) AS full_name,
				info.username AS username,
				sections.title AS fac, sections.acronym AS acr, pics.path AS img_path,
				questions.* FROM ". TABLE_QUESTIONS ." AS questions

				INNER JOIN ". TABLE_USERS ." AS students ON students.id = questions.uid
				INNER JOIN ". TABLE_INFO ." AS info ON info.id = questions.uid
				INNER JOIN ". TABLE_SECTIONS ." AS sections ON sections.id = questions.section
				LEFT JOIN ". TABLE_PROFILE_PICS ." AS pics ON pics.user_id = questions.uid

				WHERE questions.id = :id";

		$stmt = $connection->prepare($sql);

		if(!$stmt->execute([':id' => $PostID])){
			$error = $stmt->errorInfo();
			return $error[2];
		}

		$row = $stmt->fetch(PDO::FETCH_OBJ);
		if(!is_object($row)) return false;
		
		if(empty($row->img_path)) $row->img_path = DEF_PIC;

		return $row;
	}

	/**
	 * get all questions
	 *
	 * @param string $limit used for pagination
	 * @param string $offset used for pagination
	 * @param boolean $adm for admin access
	 *
	 * @return object
	 */
	public function get_questions($limit='', $offset=0, $adm=false, $priv=false){
		global $connection;

		$sql = "SELECT students.id AS uid, CONCAT(students.firstName, ' ', students.lastName) AS full_name,
				info.username AS username,
				sections.title AS fac, pics.path AS img_path,
				section.acronym AS acr, section.id AS fid, section.title AS fac,
				questions.* FROM ". TABLE_QUESTIONS ." AS questions

				INNER JOIN ". TABLE_USERS ." AS students ON students.id = questions.uid
				INNER JOIN ". TABLE_INFO ." AS info ON info.id = questions.uid
				INNER JOIN ". TABLE_SECTIONS ." AS sections ON sections.id = questions.section
				LEFT JOIN ". TABLE_PROFILE_PICS ." AS pics ON pics.user_id = questions.uid
				INNER JOIN ". TABLE_SECTIONS ." AS section ON section.id = questions.section";

		$sql .= " WHERE 1=1";

		if(!$adm) $sql .= " AND questions.status = 1";
			elseif($priv) $sql .= " AND questions.status != 1";

		if(!empty($this->section)) $sql .= " AND section.id = '$this->section'";

		$sql .= " ORDER BY questions.created DESC";

		if(!empty($limit)) $sql .= " LIMIT {$limit} OFFSET {$offset}";

		$stmt = $connection->prepare($sql);

		if(!$stmt->execute()){
			$error = $stmt->errorInfo();
			return $error[2];
		}

		$obj = [];
		
		while($row = $stmt->fetch(PDO::FETCH_OBJ)){

			unset($row->password);

			if(empty($row->img_path)) $row->img_path = DEF_PIC;

			$obj[] = $row;
		}

		return $obj;
	}

	/**
	 * get questions count
	 *
	 * @param string $sec section number
	 * @param boolean $priv get hidden records
	 *
	 * @return int
	 */
	public static function get_questions_count($sec=null, $priv=false) {
		global $connection;

		$sql = "SELECT COUNT(*) AS count FROM " . TABLE_QUESTIONS . " WHERE 1=1";

		if(!is_null($sec)) $sql .= " AND section = {$sec}";

		if($priv) $sql .= " AND status != 1";

		return (int) $connection->query($sql)->fetch()['count'];

	}

	/**
	 * get all questions by a user
	 *
	 *
	 * @return object
	 */
	public function get_questions_by_user($UserID, $limit=true, $count=10, $order='CREATED DESC'){
		global $connection;

		$sql = "SELECT students.id AS uid, CONCAT(students.firstName, ' ', students.lastName) AS full_name,
				info.username AS username, sections.title AS fac,
				section.acronym AS acr, section.id AS fid, section.title AS fac,
				questions.* FROM ". TABLE_QUESTIONS ." AS questions

				INNER JOIN ". TABLE_USERS ." AS students ON students.id = questions.uid
				INNER JOIN ". TABLE_INFO ." AS info ON info.id = questions.uid
				INNER JOIN ". TABLE_SECTIONS ." AS sections ON sections.id = questions.section
				INNER JOIN ". TABLE_SECTIONS ." AS section ON section.id = questions.section

				WHERE uid = :uid AND questions.status != 0";

		$sql .= " ORDER BY {$order}";

		if($limit) $sql .= " LIMIT {$count}";

		$stmt = $connection->prepare($sql);

		if(!$stmt->execute([':uid' => $UserID])){
			$error = $stmt->errorInfo();
			return $error[2];
		}

		$obj = [];

		while($row = $stmt->fetch(PDO::FETCH_OBJ)){

			unset($row->password);

			if(empty($row->img_path)) $row->img_path = DEF_PIC;

			$obj[] = $row;
		}

		return $obj;
	}

	/**
	 * get all sections
	 *
	 * @return array
	 */
	public function get_sections(){
		global $connection;

		$sql = "SELECT * FROM ".TABLE_SECTIONS."";

		$sections = $connection->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		
		return $sections;
	}

	/**
	 * upvote a post/comment
	 *
	 * @param $PostID int
	 * @param $uid int
	 *
	 * @return boolean
	 */
	public static function upvote($PostID, $uid){
		$database = new Database();

		$data = ['post_id' => $PostID, 'user_id' => $uid];
		$insert = $database->insert_data(TABLE_POINTS, $data);

		return $insert;
	}

	/**
	 * upvote a post/comment
	 *
	 * @param $PostID int
	 * @param $uid int
	 *
	 * @return boolean
	 */
	public static function downvote($PostID, $uid){
		global $connection;

		$sql = "DELETE FROM `points`
				WHERE post_id = {$PostID}
				AND user_id = {$uid}
				LIMIT 1";

		$stmt = $connection->prepare($sql);

		if(!$stmt->execute()){
			$error = ($stmt->errorInfo());
			return $error[2];
		}
		return true;
	}

	/**
	 * check if user has voted on a post/comment
	 *
	 * @param $PostID int
	 * @param $uid int
	 *
	 * @return boolean
	 */
	public static function has_voted($PostID, $uid){
		global $connection;

		$sql = "SELECT 1 FROM ". TABLE_POINTS ."
				WHERE post_id = {$PostID}
				AND user_id = {$uid}";

		return (bool)$connection->query($sql)->fetch();
	}

	/**
	 * get total votes on a post/comment
	 *
	 * @param $PostID int
	 *
	 * @return string
	 */
	public static function get_votes($PostID){
		global $connection;
		
		$sql = "SELECT SUM(points.votes) AS count from ". TABLE_POINTS ." AS points
				WHERE points.post_id = :post_id";

		$stmt = $connection->prepare($sql);
		$stmt->bindParam(':post_id', $PostID, PDO::PARAM_INT);

		if(!$stmt->execute()){
				$error = ($stmt->errorInfo());
				return $error[2];
			}
		return $stmt->fetch()['count'];
	}

	/**
	 * get comments posted on a question
	 *
	 * @return array
	 */
	public function get_Qcomments($id=''){

		if(empty($id)) $id = $this->PostID;

		return Comment::get_comments($id);
	}

	/**
	 * delete a question
	 *
	 * @return boolean
	 */
	public function delete(){
		global $connection;

		$table = static::$table;

		$comments = $this->get_Qcomments();

		while ($comments) {
			$comment = array_shift($comments);
			Comment::deleteComment($comment->id);
		}

		// delete question points
		$sql = "DELETE FROM ". TABLE_POINTS ." WHERE post_id = {$this->PostID}";
		$connection->exec($sql);

		// delete the question
		//$sql = "UPDATE {$table} SET status = 0 WHERE id = {$this->PostID}";
		$sql = "DELETE FROM {$table} WHERE id = {$this->PostID}";
		$connection->exec($sql);
		return true;
	}

	/**
	 * edit a question
	 *
	 * @param $content string
	 *
	 * @return array|string
	 */
	public function edit_question($content){
		$database = new Database();

		$update = $database->update_data(TABLE_QUESTIONS, 'content', $content, 'id', $this->PostID);

		if($update !== true || $database->error){
			return array_shift($database->errors);
		}

		return true;
	}

	/**
	 * report a post/comment
	 *
	 * @param int $CommentID
	 * @param string $content
	 * @param int $user_id
	 *
	 * @return boolean|string
	 */
	public function report($CommentID, $content, $user_id){
		$database = new Database();

		$data = ['post_id' => $CommentID, 'content' => $content, 'reporter' => $user_id];

		$report = $database->insert_data(TABLE_REPORTS, $data);

		if($report === true){

			return true;
		} else {

			return $database->errors;
		}
	}

	/**
	 * get total reports on a post/comment
	 *
	 * @param string $ord return order
	 * @param int $PostID
	 * @param boolean $unq
	 * @param int $limit
	 * @param int $offset
	 *
	 * @return object|boolean false
	 */
	public static function get_reports($PostID='', $ord=null, $unq=false, $limit=false, $offset=0){
		global $connection;

		$table = static::$table;

		$sql = "SELECT rp.id AS rp_id, rp.post_id AS rp_post_id, rp.content AS r_content, rp.reporter AS reporter, rp.date AS rp_date,
				u.id AS u_id,
				{$table}.* FROM `reports` AS rp

				INNER JOIN `$table` ON rp.post_id = $table.id
				INNER JOIN ". TABLE_USERS ." AS u ON {$table}.uid = u.id";
		
		if(!empty($PostID)) $sql .= " WHERE rp.post_id = {$PostID}";
		if($unq) $sql .= " GROUP BY rp.post_id";
		
		if(!is_null($ord)) $sql .= " ORDER BY {$ord}, date DESC";
		else $sql .= " ORDER BY date DESC";

		if($limit) $sql .= " LIMIT {$limit} OFFSET {$offset}";

		$stmt = $connection->prepare($sql);

		if(!$stmt->execute()){
			$error = ($stmt->errorInfo());
			return $error[2];
		}

		$result = $stmt->fetchAll(PDO::FETCH_OBJ);

		return !empty($result) ? $result : false;
	}

	/**
	 * get reports count
	 *
	 * @param int $id post id
	 * @param boolean $sec restrict count to a section
	 *
	 * @return int
	 */
	public static function get_reports_count($id='', $sec=false){
		global $connection;

		$table = static::$table;

		$sql = "SELECT count(*) AS count FROM `reports`";

		if($sec) $sql .= " INNER JOIN `$table` ON reports.post_id = $table.id";

		if(!empty($id)) $sql .= " WHERE reports.post_id = {$id}";

		$stmt = $connection->query($sql);


		$results = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

		return $results;
	}

	/**
	 * makes a question public
	 *
	 * @param $PostID int
	 *
	 * @return boolean|string
	 */
	public static function Publish($PostID){
		$database = new Database();

		$fields = ['status'];
		$values = [1];

		$update = $database->update_data(TABLE_QUESTIONS, $fields, $values, 'id', $PostID);

		if($update === true){
			return true;
		} else {
			return array_shift($database->errors);
		}
	}

	/**
	 * makes a question private
	 *
	 * @param $PostID int
	 *
	 * @return boolean|string
	 */
	public static function unPublish($PostID){
		$database = new Database();

		$fields = ['status'];
		$values = [2];

		$update = $database->update_data(TABLE_QUESTIONS, $fields, $values, 'id', $PostID);

		if($update === true){
			return true;
		} else {
			return array_shift($database->errors);
		}
	}

	/**
	 * get all posts by a user id
	 *
	 * @param $uid int
	 *
	 * @return object
	 */
	public function get_posts_by_user($uid){
		global $connection;
		
		$sql = "SELECT * FROM ". TABLE_QUESTIONS ."
				WHERE status = 1
				AND uid = {$uid}
				ORDER BY created DESC
				";
		$stmt = $connection->query($sql);
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	/**
	 * save a post/comment
	 *
	 *
	 * @return mixed
	 */
	public function save_post(){
		$database = new Database();

		$data = ['post_id' => $this->PostID, 'user_id' => USER_ID];

		$insert = $database->insert_data(TABLE_SAVED, $data);

		if($insert == true){

			return true;
		} elseif($database->errors[1] == 1062) { // duplicate

			return "You have already saved this post.";
		} else {

			return $database->errors[2];
		}
	}

	/**
	 * get saved posts
	 *
	 * @param int $UserID
	 * 
	 * @return array
	 */
	public function get_saved($UserID){
		global $connection;

		$sql = "SELECT saved.post_id FROM ". TABLE_SAVED ." WHERE user_id = {$UserID}";

		$stmt =  $connection->query($sql);

		$ids = [];
		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$ids[] = $row[0];
		}

		$posts = [];

		foreach ($ids as $id) {
			$posts[] = $this->get_question($id);
		}

		return $posts;
	}

	/**
	 * remove a saved post
	 *
	 * @param int $PostID
	 * 
	 * @return boolean
	 */
	public static function remove_saved($PostID){
		global $connection;

		$sql = "SELECT * FROM ". TABLE_SAVED ." WHERE post_id = {$PostID}";
		$row = $connection->query($sql)->fetch(PDO::FETCH_ASSOC);

		if(USER_ID !== $row['user_id']) die(json_encode(['status' => false, 'id' => $PostID, 'err' => 'Authentication error.']));


		$sql = "DELETE FROM ". TABLE_SAVED ." WHERE post_id = {$PostID} LIMIT 1";
		$stmt =  $connection->exec($sql);

		return true;	
	}

}
?>