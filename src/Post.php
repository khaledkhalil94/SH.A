<?php  
/**
* 
*/
class Post extends QNA {
	
	function __construct() {
		# code...
	}

	public function get_post($PostID){
		global $connection;

		$sql = "SELECT users.id AS uid, users.firstName, CONCAT(users.firstName, ' ', users.lastName) AS full_name, info.username,
				activity.*, pic.path AS img_path FROM ". TABLE_USERS ." AS users

				INNER JOIN ". TABLE_INFO ." AS info ON users.id = info.id 
				INNER JOIN ". TABLE_ACTIVITY ." AS activity ON users.id = activity.poster_id
				LEFT JOIN ". TABLE_PROFILE_PICS ." AS pic ON users.id = pic.user_id

				WHERE activity.id = {$PostID}";

		$stmt = $connection->prepare($sql);

		if(!$stmt->execute()){
			$error = $stmt->errorInfo();
			echo $error[2];
		}

		return $stmt->fetch(PDO::FETCH_OBJ);
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

$post = new Post();
?>