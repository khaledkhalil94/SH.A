<?php 
class Messages extends User {
	
	protected static $table_name="messages";
	public $id, $user_id, $title, $sender_id, $subject, $date;
	//protected static ;
	protected static $db_fields = array();

	public function __construct(){
		global $db_fields;
		self::$db_fields = array_keys((array)$this);
	}

	public static function sendMsg(){
		global $session;
		$msg = parent::instantiate($_POST);
		if($msg->create()){
			$session->message("Message has been sent successfully.", ".");
		}

		unset($this->sender_id);
	}

	public static function getMsgs($id){
		global $connection;
		$sql = "SELECT * FROM `messages` WHERE user_id = {$id} AND deleted = 0 ORDER BY seen ASC, Date DESC";
		return parent::find_by_sql($sql);
	}

	public static function getMsgsboth($id, $selfId){
		global $connection;
		$sql = "SELECT * FROM `messages` WHERE user_id = {$id} AND sender_id = {$selfId} AND deleted = 0 ORDER BY seen ASC, Date DESC";
		return parent::find_by_sql($sql);
	}

	public static function getSenderMsgs($selfId, $id){
		global $connection;
		$sql = "SELECT * FROM `messages` WHERE
				user_id = {$selfId} AND sender_id = {$id}
				OR user_id = {$id} AND sender_id = {$selfId} 
				AND deleted = 0 
				ORDER BY Date DESC";
		return parent::find_by_sql($sql);
	}

	public static function getMsg($id){
		global $connection;
		$sql = " AND deleted = 0";
		return parent::find_by_id($id, $sql);
	}

	public static function getMsgsCount($id){
		global $connection;
		$sql = "SELECT count(*) FROM `messages` WHERE user_id = {$id} AND deleted = 0 AND seen = 0";
		$stmt = $connection->query($sql);
		$res = $stmt->fetch()[0];
		return $res;
	}

	public static function isSeen($id){
		global $connection;
		$sql = "SELECT seen FROM `messages` WHERE id = {$id} AND deleted = 0";
		$stmt = $connection->query($sql);
		$res = $stmt->fetch()[0];
		return $res == 1 ? true : false;
	}

	public static function hasMsgs($id){
		return self::getMsgsCount($id) > 0 ? true : false;
	}

	public static function Msgs($id){
		$msgCount = self::getMsgsCount($id);
		if ($msgCount > 1){
			echo "You have {$msgCount} new messages";
		} elseif ($msgCount = 1){
			echo "You have a new message";
		}
    }

	public static function msgSeen($id){
		global $connection;
		$sql = "UPDATE `messages` SET seen = 1 WHERE id = {$id}";
		$connection->query($sql);
		return true;
	}

	public static function msgUnSee($id){
		global $connection;
		$sql = "UPDATE `messages` SET seen = 0 WHERE id = {$id}";
		$connection->query($sql);
		return true;
	}

	public static function deleteMsg($id){
		global $connection;
		$sql = "UPDATE `messages` SET deleted = 1 WHERE id = {$id}";
		$connection->query($sql);
		return true;
    }

}


?>