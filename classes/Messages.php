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

	// get all undeleted messages by user id
	public static function getMsgs($id){
		global $connection;
		$sql = "SELECT * FROM `messages` WHERE user_id = {$id} AND deleted = 0 ORDER BY seen ASC, Date DESC";
		return parent::find_by_sql($sql);
	}

	// get a single message by it's id
	public static function getMsg($user_id, $id){
		global $connection;
		$sql = " AND deleted = 0 AND user_id = {$user_id} LIMIT 1 ";
		return parent::find_by_id($id, $sql);
	}

	// get a single message by it's id
	public static function getMsgById($id){
		global $connection;
		return parent::find_by_id($id);
	}

	// get conversational messages between two users by their ids
	public static function getConvo($selfId, $id){
		global $connection;
		$sql = "SELECT * FROM `messages` WHERE
				deleted = 0 
				AND
				(user_id = {$selfId} AND sender_id = {$id})
				OR 
				(user_id = {$id} AND sender_id = {$selfId})
				ORDER BY Date DESC";
		return parent::find_by_sql($sql);
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
	public static function msgUnSee($user_id, $id){
		global $connection;
		$sql = "UPDATE `messages` SET seen = 0 WHERE id = {$id} AND user_id = {$user_id} LIMIT 1";
		$res = $connection->exec($sql);
		return (bool)$res ? true : false;
	}

	// deletes a messages (just hide it)
	public static function deleteMsg($user_id, $id){
		global $connection;
		$sql = "UPDATE `messages` SET deleted = 1 WHERE id = {$id} AND user_id = {$user_id} LIMIT 1";
		$res = $connection->exec($sql);
		return (bool)$res ? true : false;
    }

    public static function report($id){
    	global $connection;
		$sql = "UPDATE `messages` SET report = 1 WHERE id = {$id} LIMIT 1";
		$res = $connection->exec($sql);
		return (bool)$res ? true : false;
    }

    public static function unReport($id){
    	global $connection;
		$sql = "UPDATE `messages` SET report = 0 WHERE id = {$id} LIMIT 1";
		$res = $connection->exec($sql);
		return (bool)$res ? true : false;
    }

    public static function getReports(){
    	global $connection;
    	return parent::find_by_sql("SELECT * FROM messages WHERE report = 1");
    }

}


?>