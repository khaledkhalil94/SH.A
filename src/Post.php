<?php
require_once('init.php');

/**
* 
*/
class Post extends QNA {
	
	public $PostID;

	static $table = TABLE_ACTIVITY;

	public function get_post($PostID, $c=false){
		global $connection;

		$sql = "SELECT users.id AS r_id, users.firstName, CONCAT(users.firstName, ' ', users.lastName) AS full_name, info.username,
				pic.path AS img_path, activity.* FROM ". TABLE_USERS ." AS users

				INNER JOIN ". TABLE_INFO ." AS info ON users.id = info.id 
				INNER JOIN ". TABLE_ACTIVITY ." AS activity ON users.id = activity.poster_id
				LEFT JOIN ". TABLE_PROFILE_PICS ." AS pic ON users.id = pic.user_id

				WHERE activity.id = {$PostID}";

		$stmt = $connection->prepare($sql);

		if(!$stmt->execute()){
			$error = $stmt->errorInfo();
			echo $error[2];
		}

		$post = $stmt->fetch(PDO::FETCH_ASSOC);

		if(is_array($post) && $c) return $post;

		// not a self post
		if($post['user_id'] !== $post['poster_id']){

			$receiver = $this->get_post_to($post['user_id']);
			$post = array_merge($post, $receiver);

		}

		if(!is_array($post)) return false;

		return (object) $post;
	}

	private function get_post_to($uid){
		global $connection;

		$sql = "SELECT users.id AS uid, users.firstName AS r_fn, CONCAT(users.firstName, ' ', users.lastName) AS r_full_name, info.username AS r_un
				FROM ". TABLE_USERS ." AS users

				INNER JOIN ". TABLE_INFO ." AS info ON users.id = info.id 

				WHERE users.id = {$uid}";

		return $connection->query($sql)->fetch(PDO::FETCH_ASSOC);
	}

	public function get_posts($UserID){
		global $connection;

		$sql = "SELECT users.id AS uid, users.firstName, CONCAT(users.firstName, ' ', users.lastName) AS full_name, info.username,
				activity.*, pic.path AS img_path FROM ". TABLE_USERS ." AS users

				INNER JOIN ". TABLE_INFO ." AS info ON users.id = info.id 
				INNER JOIN ". TABLE_ACTIVITY ." AS activity ON users.id = activity.poster_id
				LEFT JOIN ". TABLE_PROFILE_PICS ." AS pic ON users.id = pic.user_id

				WHERE activity.user_id = {$UserID}";

		$stmt = $connection->prepare($sql);

		if(!$stmt->execute()){
			$error = $stmt->errorInfo();
			$this->error = true;
			$errMsg = $error[2];
			return $errMsg;
		}

		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}


}

?>