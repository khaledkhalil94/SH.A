<?php 
require_once('init.php');
/**
* 
*/
class QNA extends User {


	public static function get_question($id){
		global $connection;

		$sql = "SELECT students.id AS uid, CONCAT(students.firstName, ' ', students.lastName) AS full_name,
				login_info.username AS username,
				faculties.title AS fac, profile_pic.path AS img_path,
				questions.* FROM `questions`
				INNER JOIN `students` ON students.id = questions.uid
				INNER JOIN `login_info` ON login_info.id = questions.uid
				INNER JOIN `faculties` ON faculties.id = questions.faculty_id
				LEFT JOIN `profile_pic` ON profile_pic.user_id = questions.uid

				WHERE questions.id = {$id} AND questions.status != 0";

		$stmt = $connection->prepare($sql);

		if(!$stmt->execute()){
			$error = $stmt->errorInfo();
			echo $error[2];
		}

		//return array_shift($stmt->fetchAll(PDO::FETCH_OBJ));
		return $stmt->fetch(PDO::FETCH_OBJ);
		
	}

	public static function get_content($faculty_id=""){
		global $connection;
		$sql = "SELECT * FROM `questions` 
				WHERE status != 0 ";
				if (!empty($faculty_id)) {
				 $sql .= "AND faculty_id = $faculty_id ";
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
		$articles = self::get_content($q->faculty_id);

		$var = "";
		foreach ($articles as $article): 
			if ($article->id != $q->id): 
				$var .= "<li class=\"item\"><a href=\"question.php?id={$article->id}\"><p>{$article->title}</p></a></li>";
			endif; 
		endforeach;
		return $var;
	}

	public static function upvote($PostID, $uid){
		global $database;

		$data = ['post_id' => $PostID, 'user_id' => $uid];
		$insert = $database->insert_data(TABLE_POINTS, $data);

		return $insert;

	}

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

	public static function has_voted($id, $uid){
		global $connection;
		global $session;

		$sql = "SELECT 1 FROM points
				WHERE post_id = {$id}
				AND user_id = {$uid}";

		return (bool)$connection->query($sql)->fetch();
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
		$author =  Student::find_by_id($post->uid);
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

				return true;
			}
		}
		return false;
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

	public function get_reports($table="", $PostID){
		global $connection;

		$sql = "SELECT CONCAT(students.firstName, ' ', students.lastName) AS reporterName,
				reports.* FROM `reports`
				INNER JOIN `$table` ON reports.post_id = $table.id 
				INNER JOIN `students` ON reports.reporter = students.id 
				WHERE reports.post_id = {$PostID}";

		$stmt = $connection->prepare($sql);

		if(!$stmt->execute()){
			$error = ($stmt->errorInfo());
			echo $error[2];
		}

		$result = $stmt->fetchAll(PDO::FETCH_OBJ);

		return $result;
		return !empty($result) ? $result->count : false;
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

	public static function did_report($post_id, $uid){
		global $connection;

		$sql = "SELECT 1 FROM `reports` WHERE post_id = {$post_id} AND reporter = {$uid}";

		$stmt = $connection->query($sql);
		return $stmt->fetch()[1];
	}

	public static function unPublish($id){
		return parent::query("UPDATE `questions` SET status = 2 WHERE id = {$id}");
	}

	public static function Publish($id){
		return parent::query("UPDATE `questions` SET status = 1 WHERE id = {$id}");
	}

}
$QNA = new QNA();
 ?>