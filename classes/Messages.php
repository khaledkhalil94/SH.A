<?php 
require_once('init.php');
class Messages extends User {
	
	protected static $table_name="messages";
	public $id, $user_id, $title, $sender_id, $subject, $date;
	//protected static ;
	protected static $db_fields = array();

	public function __construct(){
		global $db_fields;
		self::$db_fields = array_keys((array)$this);
	}

	// send a new message via POST request
	public static function sendMsg(){
		global $session;
		$msg = parent::instantiate($_POST);
		if($msg->create()){
			$session->message("Message has been sent successfully.", ".");
		}

		unset($this->sender_id);
	}

	// get all visible messages by user id
	public static function getMsgs($id){
		global $connection;
		$sql = "SELECT 
				profile_pic.path AS img_path,
				CONCAT(students.firstName, ' ', students.lastName) AS u_fullname,
				messages.* FROM `messages` 
				LEFT JOIN `students` ON messages.sender_id = students.id
				LEFT JOIN `profile_pic` ON messages.sender_id = profile_pic.user_id
				WHERE messages.user_id = {$id} AND deleted = 0 
				ORDER BY seen ASC, Date DESC";
		$stmt = $connection->prepare($sql);
		if (!$stmt->execute()) {
			echo $stmt->errorInfo()[2];
		}
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	// get a single message by it's id
	public static function getMsg($id){
		global $connection;
		$sql = "SELECT students.id AS u_id, staff.id AS s_id,
				CONCAT(students.firstName, ' ', students.lastName) AS u_fullname,
				messages.* FROM `messages` 
				LEFT JOIN `students` ON messages.sender_id = students.id
				LEFT JOIN `staff` ON messages.sender_id = staff.id
				WHERE messages.id = {$id} AND deleted = 0";
		$stmt = $connection->prepare($sql);
		if (!$stmt->execute()) {
			echo $stmt->errorInfo()[2];
		}
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	// get conversational messages between two users by their ids
	public static function getConvo($selfId, $id, $limit=""){
		global $connection;
		$sql = "SELECT profile_pic.path AS img_path, staff.id AS s_id,
				CONCAT(students.firstName, ' ', students.lastName) AS u_fullname,
				messages.* FROM `messages` 
				LEFT JOIN `students` ON messages.sender_id = students.id
				LEFT JOIN `staff` ON messages.sender_id = staff.id
				LEFT JOIN `profile_pic` ON {$id} = profile_pic.user_id
				WHERE deleted = 0 
				AND
				(messages.user_id = {$selfId} AND messages.sender_id = {$id}
				OR 
				messages.user_id = {$id} AND messages.sender_id = {$selfId})
				ORDER BY Date DESC ";
		if(!empty($limit)) $sql .= "LIMIT {$limit}";
				
		$stmt = $connection->prepare($sql);
		if (!$stmt->execute()) {
			echo $stmt->errorInfo()[2];
		}
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	// get count of unread messages by user id
	public static function getMsgsCount($id){
		global $connection;
		$msql = " WHERE user_id = {$id} AND deleted = 0 AND seen = 0";
		return parent::get_count($msql);
	}

	// checks if the message is read or not
	public static function isSeen($id){
		global $connection;
		$sql = "SELECT seen FROM `messages` WHERE id = {$id} AND deleted = 0";
		$stmt = $connection->query($sql);
		$res = $stmt->fetch()[0];
		return $res == 1 ? true : false;
	}

	// checks if the user has new message(s)
	public static function hasMsgs($id){
		return self::getMsgsCount($id) > 0 ? true : false;
	}

	// displays how many messages the user has
	public static function Msgs($id){
		$msgCount = self::getMsgsCount($id);
		if ($msgCount > 1){
			echo "You have {$msgCount} new messages";
		} elseif ($msgCount = 1){
			echo "You have a new message";
		}
    }

    // marks a message as seen once the user opens it
	public static function msgSeen($user_id, $id){
		global $connection;
		$sql = "UPDATE `messages` SET seen = 1 WHERE id = {$id} AND user_id = {$user_id} LIMIT 1";
		$res = $connection->exec($sql);
		return (bool)$res ? true : false;
	}

	// marks a message as unread
	public static function msgUnSee($id){
		exit("as");
		global $connection;
		$sql = "UPDATE `messages` SET seen = 0 WHERE id = {$id} LIMIT 1";
		$stmt = $connection->prepare($sql);
		if (!$stmt->execute()) {
			echo $stmt->errorInfo()[2];
			exit;
		}
		exit("asd");
		return parent::query($sql); exit;
		$res = $connection->exec($sql);
		return (bool)$res ? true : false;
	}

	// deletes a messages (just hide it)
	public static function deleteMsg($user_id, $id){
		global $connection;
		$sql = "UPDATE `messages` SET deleted = 1 WHERE id = {$id}
		AND (user_id OR sender_id = {$user_id})
		LIMIT 1";
		$res = $connection->exec($sql);
		return (bool)$res ? true : false;
    }
}


?>