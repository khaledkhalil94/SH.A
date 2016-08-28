<?php 
require_once('init.php');
/**
* 
*/
class QNA extends User {

	public $PostID;

	public function __construct($PostID=null){
		$this->PostID = (int)$PostID;
	}

	/**
	 * create a new question
	 *
	 * @param $title string
	 * @param $content string
	 * @param $section int
	 *
	 * @return int|array
	 */
	public function create($data){
		global $database;

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
	 * @return object
	 */
	public static function get_question($PostID){
		global $connection;

		$sql = "SELECT students.id AS uid, CONCAT(students.firstName, ' ', students.lastName) AS full_name,
				info.username AS username,
				sections.title AS fac, pics.path AS img_path,
				questions.* FROM ". TABLE_QUESTIONS ." AS questions

				INNER JOIN ". TABLE_USERS ." AS students ON students.id = questions.uid
				INNER JOIN ". TABLE_INFO ." AS info ON info.id = questions.uid
				INNER JOIN ". TABLE_SECTIONS ." AS sections ON sections.id = questions.section
				LEFT JOIN ". TABLE_PROFILE_PICS ." AS pics ON pics.user_id = questions.uid

				WHERE questions.id = {$PostID}";

		$stmt = $connection->prepare($sql);

		if(!$stmt->execute()){
			$error = $stmt->errorInfo();
			echo $error[2];
		}

		return $stmt->fetch(PDO::FETCH_OBJ);
	}

	/**
	 * get all questions
	 *
	 *
	 * @return object
	 */
	public function get_questions($section=""){
		global $connection;

		$sql = "SELECT students.id AS uid, CONCAT(students.firstName, ' ', students.lastName) AS full_name,
				info.username AS username,
				sections.title AS fac, pics.path AS img_path,
				section.acronym AS acr, section.id AS fid, section.title AS fac,
				questions.* FROM ". TABLE_QUESTIONS ." AS questions

				INNER JOIN ". TABLE_USERS ." AS students ON students.id = questions.uid
				INNER JOIN ". TABLE_INFO ." AS info ON info.id = questions.uid
				INNER JOIN ". TABLE_SECTIONS ." AS sections ON sections.id = questions.section
				INNER JOIN ". TABLE_PROFILE_PICS ." AS pics ON pics.user_id = questions.uid
				INNER JOIN ". TABLE_SECTIONS ." AS section ON section.id = questions.section";

		if(!empty($section)) $sql .= " WHERE section.id = '$section' AND questions.status != 0";

		$sql .= " ORDER BY created DESC";

		$stmt = $connection->prepare($sql);

		if(!$stmt->execute()){
			$error = $stmt->errorInfo();
			return $error[2];
		}

		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	/**
	 * get all sections
	 *
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
		global $database;

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
				INNER JOIN ". TABLE_QUESTIONS ." AS questions ON points.post_id = questions.id
				WHERE questions.id = {$PostID}";
				$stmt = $connection->prepare($sql);
		if(!$stmt->execute()){
				$error = ($stmt->errorInfo());
				return $error[2];
			}
		return $connection->query($sql)->fetch()['count'];
	}

	/**
	 * get comments posted on a question
	 *
	 * @return array
	 */
	public function get_comments(){
		return Comment::get_comments($this->PostID);
	}

	/**
	 * delete a question
	 *
	 * @return boolean
	 */
	public function delete(){
		global $connection;

		$comments = $this->get_comments();

		while ($comments) {
			$comment = array_shift($comments);
			Comment::deleteComment($comment->id);
		}

		// delete question points
		$sql = "DELETE FROM ". TABLE_POINTS ." WHERE post_id = {$this->PostID}";
		$connection->exec($sql);

		// delete the question
		$sql = "UPDATE ". TABLE_QUESTIONS ." SET status = 0 WHERE id = {$this->PostID}";
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
		global $database;

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
		global $database;

		$data = ['post_id' => $CommentID, 'content' => $content, 'reporter' => $user_id];

		$report = $database->insert_data(TABLE_REPORTS, $data);

		if($report === true){

			return true;
		} else {

			return array_shift($database->errors);
		}
	}

	/**
	 * get total reports on a post/comment
	 *
	 * @param $table string
	 * @param $PostID int
	 *
	 * @return boolean|string
	 */
	public function get_reports($table, $PostID){
		global $connection;

		$sql = "SELECT CONCAT(students.firstName, ' ', students.lastName) AS reporterName,
				reports.* FROM `reports`
				INNER JOIN `$table` ON reports.post_id = $table.id 
				INNER JOIN ". TABLE_USERS ." AS students ON reports.reporter = students.id 
				WHERE reports.post_id = {$PostID}";

		$stmt = $connection->prepare($sql);

		if(!$stmt->execute()){
			$error = ($stmt->errorInfo());
			return $error[2];
		}

		$result = $stmt->fetchAll(PDO::FETCH_OBJ);

		return !empty($result) ? $result : false;
	}

	/**
	 * makes a question public
	 *
	 * @param $PostID int
	 *
	 * @return boolean|string
	 */
	public static function Publish($PostID){
		global $database;

		$fields = 'status';
		$values = 1;

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
		global $database;

		$fields = 'status';
		$values = 2;

		$update = $database->update_data(TABLE_QUESTIONS, $fields, $values, 'id', $PostID);

		if($update === true){
			return true;
		} else {
			return array_shift($database->errors);
		}
	}

	public static function get_content($section=""){
		global $connection;
		$sql = "SELECT * FROM `questions` 
				WHERE status = 1 ";
				if (!empty($section)) {
				 $sql .= "AND section = $section ";
				}
		$sql .= "ORDER BY created DESC
				";
		$stmt = $connection->query($sql);
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public static function get_content_by_user($uid){
		global $connection;
		$sql = "SELECT * FROM `questions` 
				WHERE status = 1
				AND uid = {$uid}
				ORDER BY created DESC
				";
		$stmt = $connection->query($sql);
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public static function sidebar_content($q){
		$articles = self::get_content($q->section);

		$var = "";
		foreach ($articles as $article): 
			if ($article->id != $q->id): 
				$var .= "<li class=\"item\"><a href=\"question.php?id={$article->id}\"><p>{$article->title}</p></a></li>";
			endif; 
		endforeach;
		return $var;
	}

	public static function reports($table, $id=""){
		global $connection;

		$sql = "SELECT DISTINCT reports.post_id FROM reports
				INNER JOIN `{$table}` ON reports.post_id = {$table}.id ";
		if (!empty($id)) {
			$sql .= "WHERE post_id = {$id}";
			return $connection->query($sql)->fetch();
		}
		$array = array();
		$stmt = $connection->prepare($sql);
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$array[] = $row[0];
		}
		return $array;
	}

	public static function did_report($post_id, $uid){
		global $connection;

		$sql = "SELECT 1 FROM `reports` WHERE post_id = {$post_id} AND reporter = {$uid}";

		$stmt = $connection->query($sql);
		return $stmt->fetch()[1];
	}
}
$QNA = new QNA();
 ?>