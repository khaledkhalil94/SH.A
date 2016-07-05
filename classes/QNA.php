<?php 
require_once('init.php');
/**
* 
*/
class QNA extends User {
	public $id, $faculty_id, $uid, $title, $content, $created, $last_modified, $status="1";
	protected static $table_name="questions";
	protected static $db_fields = array();

	public function __construct(){
		global $db_fields;
		self::$db_fields = array_keys((array)$this);
	}

	public static function get_content(){
		global $connection;
		$sql = "SELECT * FROM `questions` 
				WHERE status = 1
				ORDER BY created DESC
				";
		$stmt = $connection->query($sql);
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public static function sidebar_content($id){
		$article = self::find_by_id($id);
		$articles = self::get_content($article->faculty_id);
		foreach ($articles as $article): 
			if ($article->id != $id): 
				return "<a href=\"question.php?id={$article->id}\"><p>{$article->title}</p></a>";
			endif; 
		endforeach;
	}


	public static function upvote($post, $uid){
		global $connection;
		global $session;

		//if not logged in
		if(!$session->is_logged_in()) {
			$session->message("You must login to upvote.", "question.php?id={$post->id}", "warning");
			return false;
		} elseif($post->uid === $session->user_id){
			$session->message("You can't upvote your own post.", "question.php?id={$post->id}", "warning");
			return false;
		}

		$sql = "INSERT INTO `points` (post_id, user_id) 
		VALUES ({$post->id}, {$uid})";
		$stmt = $connection->prepare($sql);

		if(!$stmt->execute()){
			$error = ($stmt->errorInfo());
			$session->message($error[2], "", "danger");
		}
		$session->message("Thanks for your upboat.", "question.php?id={$post->id}", "success");

	}

	public static function downvote($id, $uid){
		global $connection;
		global $session;

		//if not logged in
		if(!$session->is_logged_in()) {
			$session->message("You must login to downvote.", "question.php?id={$id}", "warning");
			return false;
		}

		$sql = "DELETE FROM `points`
				WHERE post_id = {$id}
				AND user_id = {$uid}
				LIMIT 1";
		parent::query($sql);
		$session->message("Thanks for your downboat.", "question.php?id={$id}", "success");
	}

	public static function has_voted($id, $uid){
		global $connection;
		global $session;

		$sql = "SELECT 1 FROM points
				WHERE post_id = {$id}
				AND user_id = {$uid}";
		$stmt = $connection->prepare($sql);
		if(!$stmt->execute()){
			$error = ($stmt->errorInfo());
			$session->message($error[2], "", "danger");
		}
		return $connection->query($sql)->fetch();
	}

	public static function get_votes($id){
		global $connection;
		$sql = "SELECT SUM(points.votes) AS count from `points`
				INNER JOIN `questions` ON points.post_id = questions.id
				WHERE questions.id = {$id}";
				$stmt = $connection->prepare($sql);
		if(!$stmt->execute()){
				$error = ($stmt->errorInfo());
				echo $error[2];
			}
		return $connection->query($sql)->fetch()['count'];
	}

	public static function delete($post){
		global $session;

		//if not logged in
		$author =  Student::find_by_id($post->uid) ?:  Staff::find_by_id($post->uid);
		if(!$session->is_logged_in()) {
			$session->message("You must login to delete.", "", "warning");
			return false;
		} elseif(USER_ID == $author->id || $session->adminCheck()){ // if logged user is the post's user or an admin
			$comments = Comment::get_comments($post->id);
			if (
				Comment::delete_comments($comments) &&
				parent::query("DELETE FROM `points` WHERE post_id = {$post->id}") &&
				parent::query("UPDATE `questions` SET status = 0 WHERE id = {$post->id}") // hides the post
				//parent::delete($post->id) // deletes the post
				){
				//$session->message("Question has been deleted!", ".", "success");
				return true;
			}
		}
		$session->message("You can't delete this question.", "question.php?id={$post->id}", "warning");
		return false;

	}

	public static function report($post){
		global $connection;
		global $session;
		if(!$session->is_logged_in()) {
			$session->message("You must login to report.", "", "warning");
			return false;
		}
		$reporter = USER_ID;
		$sql = "INSERT INTO `reports`
				(post_id, reporter, content, date) 
				VALUES ('{$post->id}','{$reporter}', ? ,CURRENT_TIME)";
		$stmt = $connection->prepare($sql);
		$stmt->bindParam(1, $_POST['content']);
		return $stmt->execute();
	}

	public function get_reports($table="", $post_id=""){
		global $connection;

		$sql = "SELECT $table.id as q_id, $table.content as q_content, $table.uid as q_uid,
				CONCAT(students.firstName, ' ', students.lastName) AS reporterName,
				reports.* "; 
		if (!empty($post_id)) $sql .= ", (SELECT count(*) FROM `reports` WHERE reports.post_id = {$post_id}) as count ";
		$sql .="FROM `reports`
				INNER JOIN `$table` ON reports.post_id = $table.id 
				INNER JOIN `students` ON reports.reporter = students.id ";
				if (!empty($post_id)) {
					$sql .= "WHERE reports.post_id = {$post_id} ";
				}

		$stmt = $connection->prepare($sql);

		if(!$stmt->execute()){
			$error = ($stmt->errorInfo());
			echo $error[2];
		}
		return $stmt->fetchAll(PDO::FETCH_OBJ);

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

	public static function delete_report($id){
		global $connection;
		$sql = "DELETE FROM `reports` WHERE id = {$id}";
		return parent::query($sql);
	}

}
$QNA = new QNA();
 ?>