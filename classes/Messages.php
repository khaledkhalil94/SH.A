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
				info.ual AS ual,
				messages.* FROM `messages` 
				LEFT JOIN `students` ON messages.sender_id = students.id
				LEFT JOIN `login_info` AS info ON messages.sender_id = info.id
				LEFT JOIN `profile_pic` ON messages.sender_id = profile_pic.user_id
				WHERE messages.user_id = {$id} AND deleted = 0 
				ORDER BY seen ASC, Date DESC";
		$stmt = $connection->prepare($sql);
		if (!$stmt->execute()) {
			echo $stmt->errorInfo()[2];
		}
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	// get all visible messages by user id
	public static function getDeletedMsgs($id){
		global $connection;
		$sql = "SELECT 
				profile_pic.path AS img_path,
				CONCAT(students.firstName, ' ', students.lastName) AS u_fullname,
				info.ual AS ual,
				messages.* FROM `messages` 
				LEFT JOIN `students` ON messages.sender_id = students.id
				LEFT JOIN `login_info` AS info ON messages.sender_id = info.id
				LEFT JOIN `profile_pic` ON messages.sender_id = profile_pic.user_id
				WHERE messages.user_id = {$id} AND deleted = 1 
				ORDER BY Date DESC";
		$stmt = $connection->prepare($sql);
		if (!$stmt->execute()) {
			echo $stmt->errorInfo()[2];
		}
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	// get a single message by it's id
	public static function getMsg($id){
		global $connection;
		$sql = "SELECT profile_pic.path AS img_path, students.id AS u_id,
				CONCAT(students.firstName) AS u_fullname,
				messages.* FROM `messages` 
				LEFT JOIN `students` ON messages.sender_id = students.id
				LEFT JOIN `profile_pic` ON messages.sender_id = profile_pic.user_id
				WHERE messages.id = {$id} AND deleted = 0";
		$stmt = $connection->prepare($sql);
		if (!$stmt->execute()) {
			echo $stmt->errorInfo()[2];
		}
		return $stmt->fetch(PDO::FETCH_OBJ);
	}

	// get conversational messages between two users by their ids
	public static function getConvo($selfId, $id, $limit=""){
		global $connection;
		$sql = "SELECT profile_pic.path AS img_path,
				login_info.type AS type,
				CONCAT(students.firstName, ' ', students.lastName) AS u_fullname,
				messages.* FROM `messages` 
				LEFT JOIN `students` ON messages.sender_id = students.id
				LEFT JOIN `profile_pic` ON {$id} = profile_pic.user_id
				INNER JOIN `login_info` ON {$id} = login_info.id
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

	// deletes a messages 
	public static function deleteMsg($id){
		$sql = "UPDATE `messages` SET deleted = 1 WHERE id = {$id}";

		return Database::xcute($sql);
	}

	// hides a messages
	public static function hideMsg($id){

		// TODO: check if message is already hidden or not

		$sql = "UPDATE `messages` SET deleted = 1 WHERE id = {$id}";

		return Database::xcute($sql);
	}

	// unHides a messages
	public static function unHideMsg($id){

		$sql = "UPDATE `messages` SET deleted = 0 WHERE id = {$id}";

		return Database::xcute($sql);
	}

	// get count of unread messages by user id
	public static function getMsgsCount($id){
		global $connection;
		$msql = " WHERE user_id = {$id} AND deleted = 0 AND seen = 0";
		return parent::get_count($msql);
	}

	public static function getMsgsCountBySender($id){
		global $connection;
		$msql = " WHERE sender_id = {$id} AND deleted = 0 AND seen = 0";
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

	public static function displayMessages($messages, $sec='inbox'){
		$html = '';

		if(empty($messages)) {
			$html = "There are no messages";
			return $html;
		}

		$html .= "<table class='ui selectable table'>";
		$html .= "<thead>";
		$html .= "<tr>";
		$html .= "<th class='four wide'>From</th>";
		$html .= "<th class='eight wide'>Message</th>";

		if($sec == 'archive'){
			$html .= "<th class='two wide'>Move to inbox</th>";
		}

		$html .= "<th class='two wide'>Delete</th>";
		$html .= "</tr>";
		$html .= "</thead>";
		$html .= "<tbody class='messages-list'>";

		foreach ($messages as $message):
			$senderID = $message->sender_id;
			$staff = $message->ual == 1 ? true : false;
			$date = displayDate($message->date);
			$time = get_timeago($message->date);
			$subject = $message->subject;
			if (strlen($subject) > 100) $subject = substr($subject, 0, 102)."...";
			$isSeen = self::isSeen($message->id);

			$html .= "<tr class='message-row ";
			if(!$isSeen) $html .= "unread";
			$html .= "' id='msgid-$message->id' msg-id='$message->id'";
			if($staff) {
			$html .= " class=' negative'";
			}
			$html .= ">";
			$html .= "<td>";
			$html .= "<div class='ui grid'>";
			$html .= "<div class='four wide column'>";
			if ($staff){
			$html .= "<img class='ui avatar image' src=' $message->img_path '>";
			 } else { 
			$html .= "<a href='/sha/user/ $senderID /'><img class='ui avatar image' src=' $message->img_path '></a>";
			 } 
			$html .= "</div>";
			$html .= "<div class='ten wide column'>";
			if ($staff){ 
			$html .= "<span>Admin</span>";
			} else { 
			$html .= "<a href='/sha/user/$senderID'> $message->u_fullname </a>";
			}
			$html .= "<br><div class='time' title=' $date; '> $time </div>";
			$html .="</div>";
			$html .="</td>";
			$html .= "<td class='msg-content selectable'>";
			$html .= "<a style='color:black;text-decoration: none;' href='?msg=$message->id'>";
			if($message->sender_id === USER_ID) {
			$html .= "<i style='color: grey; font-size: small;' class='fa fa-reply' aria-hidden='true'></i>";
			 }
			$html .= $subject;
			$html .="</a>";

			if($sec == 'archive'){
				$html .="</td>";
				$html .="<td class='msg-remove center aligned'>";
				$html .= "<i class='undo large link icon' id='undo-msg'></i>";
				$html .="</td>";
			}

			$html .="<td class='msg-remove center aligned'>";
		
			if($sec == 'archive'){
				$html .= "<i title='Permanently delete message' class='remove large link icon' id='msg_remove'></i>";
			} else {
				$html .= "<i title='archive message' class='remove large link icon' id='msg_arch'></i>";
			}

			$html .="</td>";
			$html .="</tr>";

		endforeach;

		$html .= "</tbody>";
		$html .= "</table>";

		return $html;

	}



}


?>