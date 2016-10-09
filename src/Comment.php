<?php
require_once('init.php');
/**
*
*/
class Comment extends QNA {

	static $table = TABLE_COMMENTS;

	/**
     * inserts a new comment
     *
     * @param $data array
	 *
     * @return int(id)|string(error)
     */
	public static function new_comment($data){
		$database = new Database();

		$PostID = $data['post_id'];
		$content = $data['content'];
		$token = $data['token'];

		if(empty(trim($content))){
			die("Comment can't be empty");
		}

		if(!is_object(QNA::get_question($PostID)) && !is_array(Post::get_post($PostID, true))){
			die("Error! Post was not found.");
		}

		if(!Token::validateToken($token)){
			die("Error! Please try again later");
		}

		unset($data['token']);
		$data['uid'] = USER_ID;

		$insert = $database->insert_data(TABLE_COMMENTS, $data);

		if($insert === true && $database->error === false) { // success

			return (int)$database->lastId;

		} else {

			return array_shift($database->errors);

		}
	}

	/**
	 * get post comments
	 *
	 * @param $postID int
	 *
	 * @return object
	 */
	public static function get_comments($postID, $limit=10){
		global $connection;

		$sql = "SELECT comments.*, users.id AS uid, CONCAT(users.firstName, ' ', users.lastName) AS fullname,
				pics.thumb_path AS path FROM ". TABLE_COMMENTS ." AS comments

				INNER JOIN ". TABLE_USERS ." AS users ON users.id = comments.uid
				LEFT JOIN ". TABLE_PROFILE_PICS ." AS pics ON pics.user_id = comments.uid

				WHERE comments.post_id = :post_id AND comments.status = 1
				ORDER BY created DESC LIMIT {$limit}";

		$stmt = $connection->prepare($sql);
		$stmt->bindValue(':post_id', $postID, PDO::PARAM_INT);

		if(!$stmt->execute()){
			$error = $stmt->errorInfo();

			die($error[2]);
		}

		$obj = [];

		while($row = $stmt->fetch(PDO::FETCH_OBJ)){

			if(empty($row->path)) $row->path = DEF_PIC;

			$obj[] = $row;
		}

		return $obj;
	}

	/**
	 * get one comment
	 *
	 * @param $id int
	 *
	 * @return array|string
	 */
	public static function getComment($id) {
		global $connection;

		$sql = "SELECT comments.*,
				CONCAT(students.firstName, ' ', students.lastName) AS name,
				pics.thumb_path AS img_path FROM ". TABLE_COMMENTS ." AS comments

				INNER JOIN ". TABLE_USERS ." AS students ON comments.uid = students.id
				LEFT JOIN ". TABLE_PROFILE_PICS ." AS pics ON comments.uid = pics.user_id

				WHERE comments.id = :id
				AND comments.status = 1
				LIMIT 1";

		$stmt = $connection->prepare($sql);
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);

		if(!$stmt->execute()){
			$error = $stmt->errorInfo();
			return $error[2];
		}

		$comment = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!is_array($comment) || empty($comment)) return false;

		$comment['img_path'] = $comment['img_path'] ?: DEF_PIC;
		return $comment;
	}

	/**
	 * get count of votes on one comment
	 *
	 * @param $id int
	 *
	 * @return array|string
	 */
	public static function get_votes($id){
		global $connection;

		$sql = "SELECT SUM(points.votes) AS count from ". TABLE_POINTS ." AS points
				INNER JOIN ". TABLE_COMMENTS ." AS comments ON points.post_id = comments.id
				WHERE comments.id = {$id}";

		$stmt = $connection->prepare($sql);
		if(!$stmt->execute()){
				$error = $stmt->errorInfo();
				echo $error[2];
			}
		return $connection->query($sql)->fetch()['count'];
	}

	/**
	 * delete a comment
	 *
	 * @param $id int
	 *
	 * @return boolean|string
	 */
	public static function deleteComment($CommentID){
		global $connection;

		// delete comment from the comments table
		$sql = "DELETE FROM ". TABLE_COMMENTS ." where id = {$CommentID}";
		$connection->exec($sql);

		// delete comment points
		$sql = "DELETE FROM ". TABLE_POINTS ." where post_id = {$CommentID}";
		$connection->exec($sql);

		// delete comment reports
		$sql = "DELETE FROM ". TABLE_REPORTS ." where post_id = {$CommentID}";
		$connection->exec($sql);

		return true;
	}

	/**
	 * edit a comment
	 *
	 * @param $id int
	 *
	 * @return array|string
	 */
	public static function edit_comment($commentID, $content){
		$database = new Database();

		$update = $database->update_data(TABLE_COMMENTS, ['content'], [$content], 'id', $commentID);

		if($update !== true || $database->error){
			return array_shift($database->errors);
		}

		return true;
	}
}

?>
