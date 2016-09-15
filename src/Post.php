<?php
require_once('init.php');

/**
* 
*/
class Post extends QNA {
	
	public $PostID;

	public $errors = [];

	static $table = TABLE_ACTIVITY;

	/**
	 *
	 *
	 *
	 */
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

		$post['img_path'] = $post['img_path'] ?: DEF_PIC; 

		return (object) $post;
	}

	private function get_post_to($uid){
		global $connection;
		global $database;

		$sql = "SELECT users.id AS uid, users.firstName AS r_fn, CONCAT(users.firstName, ' ', users.lastName) AS r_full_name, info.username AS r_un
				FROM ". TABLE_USERS ." AS users

				INNER JOIN ". TABLE_INFO ." AS info ON users.id = info.id 

				WHERE users.id = :uid";


		$stmt = $database->xcute($sql, [':uid' => $uid]);

		if($database->error === TRUE) {

			$this->errors = $database->errors;
		} else {
			
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}

	}

	public function get_posts($UserID){
		global $database;

		$sql = "SELECT users.id AS uid, users.firstName, CONCAT(users.firstName, ' ', users.lastName) AS full_name, info.username,
				activity.*, pic.path AS img_path FROM ". TABLE_USERS ." AS users

				INNER JOIN ". TABLE_INFO ." AS info ON users.id = info.id 
				INNER JOIN ". TABLE_ACTIVITY ." AS activity ON users.id = activity.poster_id
				LEFT JOIN ". TABLE_PROFILE_PICS ." AS pic ON users.id = pic.user_id

				WHERE activity.user_id = :uid ORDER BY date DESC";

		$stmt = $database->xcute($sql, [':uid' => $UserID]);

		if($database->error === TRUE) {

			$this->errors = $database->errors;
		} else {

			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}
	}

	public function get_stream($uid=USER_ID){
		global $database;
		global $connection;

		// initiating the feed array
		$feed = [];


		// getting the posts data
		$sql = "SELECT DISTINCT ac.*, CONCAT(u.firstName, ' ', u.lastName) AS u_fullname, CONCAT(p.firstName, ' ', p.lastName) AS p_fullname, u.id AS u_id, p.id AS p_id,
				pic.path AS path, picp.path AS p_path FROM ". TABLE_ACTIVITY ." AS ac 
			
				INNER JOIN ". TABLE_FOLLOWING ." AS f ON ac.user_id = f.user_id OR f.follower_id
				INNER JOIN ". TABLE_USERS ." AS u ON ac.user_id = u.id
				INNER JOIN ". TABLE_USERS ." AS p ON ac.poster_id = p.id
				INNER JOIN ". TABLE_PROFILE_PICS ." AS pic ON ac.user_id = pic.user_id
				INNER JOIN ". TABLE_PROFILE_PICS ." AS picp ON ac.poster_id = picp.user_id

				WHERE f.follower_id = :uid OR ac.user_id = ". USER_ID ."
				ORDER BY date DESC";

		$stmt = $database->xcute($sql, [':uid' => $uid]);
		
		if($database->error === TRUE) {

			$this->errors = $database->errors;
		} else {

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$row['type'] = 'ac';
				$feed[] = $row;
			}
		}


		// getting user interactions data NEEDS REWEORK

		$following_ids = [];

		$sql = "SELECT fl.user_id FROM ". TABLE_FOLLOWING ." AS fl

				WHERE fl.follower_id = :uid";

		$stmt = $database->xcute($sql, [':uid' => $uid]);

		if($database->error === TRUE) {

			$this->errors = $database->errors;printX($this->errors);
		} else {

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

				$following_ids[] = $row['user_id'];
			}
		}

		$followers = [];

		foreach ($following_ids as $id) {
			$sql = "SELECT fl.*, u.firstName AS u_firstname, u_fl.firstname AS f_firstname
					FROM ". TABLE_FOLLOWING ." AS fl
					
					INNER JOIN ". TABLE_USERS ." AS u ON fl.user_id = u.id
					INNER JOIN ". TABLE_USERS ." AS u_fl ON fl.follower_id = u_fl.id

					WHERE fl.follower_id = {$id} AND fl.user_id != {$uid}";

			$stmt = $connection->query($sql);

			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$row['type'] = 'fl';
				$feed[] = $row;
			}

		}
		//printX($feed);


		// getting the comments data
		$sql = "SELECT cmt.*, cmt.created AS date, CONCAT(u.firstName, ' ', u.lastName) AS fullname, pic.path FROM ". TABLE_COMMENTS ." AS cmt


				INNER JOIN ". TABLE_FOLLOWING ." AS f ON cmt.uid = f.user_id
				INNER JOIN ". TABLE_USERS ." AS u ON cmt.uid = u.id
				INNER JOIN ". TABLE_PROFILE_PICS ." AS pic ON cmt.uid = pic.user_id

				WHERE f.follower_id = :uid
				ORDER BY date DESC";

		$stmt = $database->xcute($sql, [':uid' => $uid]);

		if($database->error === TRUE) {

			$this->errors = $database->errors;
		} else {

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				unset($row['created']); // don't need it
				unset($row['last_modified']); // don't need it
				$row['type'] = 'cmt';
				$feed[] = $row;
			}
		}


		// getting the points data
		$sql = "SELECT ps.*, u.firstName, pic.path FROM ". TABLE_POINTS ." AS ps

				INNER JOIN ". TABLE_FOLLOWING ." AS f ON ps.user_id = f.user_id
				INNER JOIN ". TABLE_USERS ." AS u ON ps.user_id = u.id
				INNER JOIN ". TABLE_PROFILE_PICS ." AS pic ON ps.user_id = pic.user_id

				WHERE f.follower_id = :uid
				ORDER BY date DESC";

		$stmt = $database->xcute($sql, [':uid' => $uid]);

		if($database->error === TRUE) {

			$this->errors = $database->errors;
		} else {

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$row['type'] = 'ps';
				$feed[] = $row;
			}
		}

		// getting the questions data
		$sql = "SELECT DISTINCT qs.*, qs.created AS date, u.firstName, pic.path FROM ". TABLE_QUESTIONS ." AS qs

				INNER JOIN ". TABLE_FOLLOWING ." AS f ON qs.uid = f.user_id
				INNER JOIN ". TABLE_USERS ." AS u ON qs.uid = u.id
				INNER JOIN ". TABLE_PROFILE_PICS ." AS pic ON qs.uid = pic.user_id

				WHERE f.follower_id = :uid AND qs.status = 1 OR qs.uid = :uid
				ORDER BY date DESC";

		$stmt = $database->xcute($sql, [':uid' => $uid]);

		if($database->error === TRUE) {

			$this->errors = $database->errors;
		} else {

			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				unset($row['created']); // don't need it
				unset($row['last_modified']); // don't need it
				$row['type'] = 'qs';
				$feed[] = $row;
			}
		}

		return $feed;
	}

	public static function PorQ($id){

		if(is_object(QNA::get_question($id))){
			return "q";
		} elseif(is_array(self::get_post($id, true))){
			return "p";
		} elseif (is_array(Comment::getComment($id))){
			return "c";
		} else {
			return NULL;
		};
	}


}

?>