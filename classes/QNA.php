<?php 
require_once('init.php');
/**
* 
*/
class QNA extends User {
	public $id, $faculty_id, $uid, $title, $content, $created, $last_modified, $status="1", $report="0";
	protected static $table_name="questions";
	protected static $db_fields = array();

	public function __construct(){
		global $db_fields;
		self::$db_fields = array_keys((array)$this);
	}

	public static function get_content($id=""){
		global $connection;
		$sql = "SELECT * FROM `questions` 
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
		} elseif($post->id === $session->user_id){
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
		$stmt = $connection->prepare($sql);

		if(!$stmt->execute()){
			$error = ($stmt->errorInfo());
			echo $error[2];
		}
		exit;
		redirect_to_D("question.php?id={$id}");

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
		if(!$session->is_logged_in()) {
			$session->message("You must login to delete.", "", "warning");
			return false;
		} elseif(USER_ID !== Student::find_by_id($post->uid)->id){ // if logged user is not the post's user
			$session->message("You can't delete this question.", "question.php?id={$post->id}", "warning");
			return false;
		}
		$comments = Comment::get_comments($post->id);
		if (
			Comment::delete_comments($comments) &&
			parent::query("DELETE FROM `points` WHERE post_id = {$post->id}") &&
			parent::delete($post->id)
			){
			$session->message("Question has been deleted!", ".", "success");
		}

	}

	public static function report($id){
		global $connection;
		global $session;
		if(!$session->is_logged_in()) {
			$session->message("You must login to report.", "", "warning");
			return false;
		}
		$sql = "UPDATE ".static::$table_name." SET report = 1
				WHERE id = {$id} LIMIT 1";
		if ($connection->query($sql)) $session->message("Question has been reported.", "question.php?id={$id}", "success");
	}

}
$QNA = new QNA();
 ?>