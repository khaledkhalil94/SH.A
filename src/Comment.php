<?php 
require_once('init.php');
/**
* 
*/
class Comment extends User {
	public $id, $post_id, $uid, $content, $created, $last_modified, $status="1";
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

	public function getComment() {
		global $connection;
		$sql = "SELECT comments.*,
				CONCAT(students.firstName, ' ', students.lastName) AS name,
				profile_pic.path AS img_path FROM `comments`
				INNER JOIN `students` ON comments.uid = students.id
				LEFT JOIN `profile_pic` ON comments.uid = profile_pic.user_id
				WHERE comments.id = {$this->id} 
				AND comments.status = 1
				LIMIT 1
				";

		$stmt = $connection->prepare($sql);
		if(!$stmt->execute()){
			$error = ($stmt->errorInfo());
			$_SESSION['err'] = $error[2];
			return $error[2];
		}
		return ($stmt->fetch(PDO::FETCH_OBJ));
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


// THIS SHIT NEEDS TO BE REWORKED
	public function insert_comment(){
		global $comment;
		$_POST = $_POST['comment'];
		if(isset($_POST['content'])){
			if (!empty(trim($_POST['content']))) {
				// random id number for the comment
				$_POST['id'] = mt_rand(400000,500000);
				return $comment->create_user();
			} else {
				return "Comment can't be empty.";
			}
		}
	}

	public function deleteComment(){
		global $connection;


		if(USER_ID !== $this->getComment()->uid){
			echo json_encode(array('status' => 'fail', 'uid' => $this->getComment()->uid, 'msg' => 'You are not the comment owners!'));
			exit;
			//return false;
		}

		$sql = "DELETE FROM `comments` where id = {$this->id}";

		$stmt = $connection->prepare($sql);
		if($stmt->execute()){
			return array('status'=>'success','message'=>'Comment has been deleted successfully.');
		} else {
			$error = ($stmt->errorInfo());
			echo $error[2];
			return false;
		}
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

	public function get_reports(){
		global $connection;

		$sql = "SELECT comments.id as c_id, comments.content as comment, 
				reports.* FROM `reports`
				INNER JOIN `comments` ON reports.post_id = comments.id
				ORDER BY date";

		$stmt = $connection->prepare($sql);

		if(!$stmt->execute()){
			$error = ($stmt->errorInfo());
			echo $error[2];
		}
		return $stmt->fetchAll(PDO::FETCH_OBJ);

	}

}
$comment = new Comment();
 ?>