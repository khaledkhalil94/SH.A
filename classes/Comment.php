<?php 
require_once('init.php');
/**
* 
*/
class Comment extends User {
	public $id, $post_id, $uid, $content, $created, $last_modified, $status="1", $report="0";
	protected static $table_name="comments";
	protected static $db_fields = array();

	public function __construct(){
		global $db_fields;
		self::$db_fields = array_keys((array)$this);
	}

	public static function get_comments($id){
		global $connection;
		$sql = "SELECT * FROM `comments`
				WHERE comments.post_id = {$id} AND comments.status = 1
				ORDER BY created DESC
				";
		$stmt = $connection->query($sql);
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public static function get_votes($id){
		global $connection;
		$sql = "SELECT SUM(points.votes) AS count from `points`
				INNER JOIN `comments` ON points.post_id = comments.id
				WHERE comments.id = {$id}";
				$stmt = $connection->prepare($sql);
		if(!$stmt->execute()){
				$error = ($stmt->errorInfo());
				echo $error[2];
			}
		return $connection->query($sql)->fetch()['count'];
	}


	public static function comment(){
		global $comment;
		global $session;
		if(isset($_POST['comment'])){
			if (!empty(trim($_POST['content']))) {
				// random id number for the comment
				$_POST['id'] = mt_rand(400000,500000);
				if ($comment->create_user($_POST)) $session->message("Your comment has been submitted successfully!", "", "success");
			} else {
				$session->message("Comment can't be empty!", "", "danger");
			}
		}
	}

	public static function delete($comment){
		global $session;

		//if not logged in
		if(!$session->is_logged_in()) {
			$session->message("You must login to upvote.", "", "warning");
			return false;
		} elseif(USER_ID !== Student::find_by_id($comment->uid)->id){
			$session->message("You can't delete this comment.", "question.php?id={$comment->id}", "warning");
			return false;
		}

		if (parent::delete($comment->id)) $session->message("Comment has been deleted!", "question.php?id={$comment->post_id}", "success");

	}

	public static function delete_comments($comments){
		global $session;
		global $connection;
		//if not logged in
		if(!$session->is_logged_in()) {
			$session->message("You must login to upvote.", "", "warning");
			return false;
		}

		while ($comments) {
			$comment = array_shift($comments);
			parent::query("DELETE FROM `points` WHERE post_id = {$comment->id}");
			parent::delete($comment->id);
		}
		return true;

	}

	public static function report($id){
		global $connection;
		global $session;
		$sql = "UPDATE ".static::$table_name." SET report = 1
				WHERE id = {$id} LIMIT 1";
		if ($connection->query($sql)) $session->message("Comment has been reported.", "question.php?id={$id}", "success");
	}

}
$comment = new Comment();
 ?>