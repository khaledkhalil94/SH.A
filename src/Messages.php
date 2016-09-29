<?php 
require_once('init.php');

class Messages {
	

	public static function sendMsg($data){
		$database = new Database();

		$token = $data['token'];
		$send_by = USER_ID;
		$send_to = $data['send_to'];
		$value = $data['value'];

		if(strlen($value) <= 0 ){
			die("Message can't be empty");
		}

		if(!Token::validateToken($token)){
			die("Token value is invalid");
		}

		$blocked = User::blocked_by_user($send_to);

		//printX($blocked); exit;
		if(in_array($send_by, $blocked)){
			return "You can't send messages to this user";
		}

		$data = array(
			'user_id' => $send_to,
			'sender_id' => $send_by,
			'subject' => $value
			);

		$insertion = $database->insert_data('messages', $data);

		if($insertion === true){
			die(json_encode(array(

				'status' => '1',
				'msg_id' => $database->lastId

				)));

		} else {
			die(json_encode($database->errors));
		}
	}

	// get all visible messages by user id
	public static function getMsgs($user_id){
		global $connection;

		$sql = "SELECT 
				pics.path AS img_path,
				CONCAT(u.firstName, ' ', u.lastName) AS u_fullname, info.username AS username,
				info.ual AS ual,
				messages.* FROM ". TABLE_MESSAGES ."
				LEFT JOIN ". TABLE_USERS ." AS u ON messages.sender_id = u.id
				LEFT JOIN ". TABLE_INFO ." AS info ON messages.sender_id = info.id
				LEFT JOIN ". TABLE_PROFILE_PICS ." AS pics ON messages.sender_id = pics.user_id
				WHERE messages.user_id = :user_id AND deleted = 0 
				ORDER BY Date DESC";

		$stmt = $connection->prepare($sql);
		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

		if (!$stmt->execute()) {
			echo $stmt->errorInfo()[2];
		}

		$obj = [];

		while($row = $stmt->fetch(PDO::FETCH_OBJ)){

			if(empty($row->img_path)) $row->img_path = DEF_PIC;
			if($row->u_fullname == ' ') $row->u_fullname = $row->username;

			$obj[] = $row;
		}

		return $obj; 
	}

	// get all visible messages by user id
	public static function getDeletedMsgs($user_id){
		global $connection;
		$sql = "SELECT 
				pics.path AS img_path,
				CONCAT(u.firstName, ' ', u.lastName) AS u_fullname,
				info.ual AS ual, info.username AS username,
				messages.* FROM ". TABLE_MESSAGES ." 
				LEFT JOIN ". TABLE_USERS ." AS u ON messages.sender_id = u.id
				LEFT JOIN ". TABLE_INFO ." AS info ON messages.sender_id = info.id
				LEFT JOIN ". TABLE_PROFILE_PICS ." AS pics ON messages.sender_id = pics.user_id
				WHERE messages.user_id = :user_id AND deleted = 1 
				ORDER BY Date DESC";

		$stmt = $connection->prepare($sql);
		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

		if (!$stmt->execute()) {
			return $stmt->errorInfo()[2];
		}

		$obj = [];

		while($row = $stmt->fetch(PDO::FETCH_OBJ)){

			if(empty($row->img_path)) $row->img_path = DEF_PIC;
			if($row->u_fullname == ' ') $row->u_fullname = $row->username;

			$obj[] = $row;
		}

		return $obj;
	}

	// get all messages sent by user
	public static function getSentMsgs($user_id){
		global $connection;

		$sql = "SELECT 
				pics.path AS img_path,
				u.firstName AS u_fullname,
				info.ual AS ual, info.username AS username,
				messages.id, messages.user_id, messages.date, messages.subject
				FROM ". TABLE_MESSAGES ." 

				LEFT JOIN ". TABLE_USERS ." AS u ON messages.user_id = u.id
				LEFT JOIN ". TABLE_INFO ." AS info ON messages.user_id = info.id
				LEFT JOIN ". TABLE_PROFILE_PICS ." AS pics ON messages.user_id = pics.user_id

				WHERE messages.sender_id = :user_id
				ORDER BY Date DESC";

		$stmt = $connection->prepare($sql);
		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

		if (!$stmt->execute()) {
			return $stmt->errorInfo()[2];
		}
		
		$obj = [];

		while($row = $stmt->fetch(PDO::FETCH_OBJ)){

			if(empty($row->img_path)) $row->img_path = DEF_PIC;
			if($row->u_fullname == '') $row->u_fullname = $row->username;

			$obj[] = $row;
		}

		return $obj;
	}

	// get a single message by it's id
	public static function getMsg($id){
		global $connection;

		$sql = "SELECT pics.path AS img_path, sender.id AS u_id,
				info.ual AS ual, info.username AS username,
				sender.firstName AS u_name,
				receiver.firstName AS r_name,
				messages.* FROM ". TABLE_MESSAGES ." 

				LEFT JOIN ". TABLE_USERS ." AS sender ON messages.sender_id = sender.id
				LEFT JOIN ". TABLE_USERS ." AS receiver ON messages.user_id = receiver.id
				LEFT JOIN ". TABLE_INFO ." AS info ON messages.sender_id = info.id
				LEFT JOIN ". TABLE_PROFILE_PICS ." AS pics ON messages.sender_id = pics.user_id

				WHERE messages.id = {$id}";

		$stmt = $connection->prepare($sql);

		if (!$stmt->execute()) {
			echo $stmt->errorInfo()[2];
		}

		$row = $stmt->fetch(PDO::FETCH_OBJ);

		if(empty($row->img_path)) $row->img_path = DEF_PIC;
		if($row->u_name == '') $row->u_name = $row->username;

		return $row;
	}

	// get conversational messages between two users by their ids
	public static function getConvo($selfId, $id, $limit=""){
		global $connection;

		$sql = "SELECT pics.path AS img_path,
				CONCAT(u.firstName, ' ', u.lastName) AS u_fullname, info.username AS username,
				msgs.* FROM ". TABLE_MESSAGES ." AS msgs

				INNER JOIN ". TABLE_USERS ." AS u ON msgs.sender_id = u.id
				LEFT JOIN ". TABLE_PROFILE_PICS ." AS pics ON msgs.sender_id = pics.user_id
				INNER JOIN ". TABLE_INFO ." AS info ON msgs.sender_id = info.id

				WHERE deleted = 0 
				AND
				(msgs.user_id = {$selfId} AND msgs.sender_id = {$id}
				OR 
				msgs.user_id = {$id} AND msgs.sender_id = {$selfId})

				ORDER BY Date DESC ";

		if(!empty($limit)) $sql .= "LIMIT {$limit}";
				
		$stmt = $connection->prepare($sql);

		if (!$stmt->execute()) {
			echo $stmt->errorInfo()[2];
		}
		
		$obj = [];

		while($row = $stmt->fetch(PDO::FETCH_OBJ)){

			if(empty($row->img_path)) $row->img_path = DEF_PIC;
			if($row->u_fullname == ' ') $row->u_fullname = $row->username;

			$obj[] = $row;
		}

		return $obj;

	}

	// deletes a messages permanently
	public static function deleteMsg($id){
		global $connection;

		$user_id = USER_ID;

		$sql = "DELETE FROM ". TABLE_MESSAGES ." WHERE id = :id AND user_id = $user_id LIMIT 1";

		$stmt = $connection->prepare($sql);
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);

		return $stmt->execute();
	}

	// hides a messages
	public static function hideMsg($id){

		// TODO: check if message is already hidden or not
		global $connection;
		
		$sql = "UPDATE ". TABLE_MESSAGES ." SET deleted = 1 WHERE id = :id";

		$stmt = $connection->prepare($sql);
		$stmt->execute([':id' =>  $id]);

		return true;
	}

	// unHides a messages
	public static function unHideMsg($id){
		$database = new Database();
		
		$sql = "UPDATE ". TABLE_MESSAGES ." SET deleted = 0 WHERE id = {$id}";

		return $database->xcute($sql);
	}

	// get count of unread messages by user id
	public static function getMsgsCount(){
		global $connection;

		$user_id = USER_ID;

		$sql = "SELECT COUNT(*) AS count FROM ". TABLE_MESSAGES ." WHERE user_id = {$user_id}
				AND deleted = 0 AND seen = 0";
		
		$res = $connection->query($sql);
		return $res->fetch()['count'];
	}

	// checks if the message is read or not
	public static function isSeen($id){
		global $connection;
		$sql = "SELECT seen FROM ". TABLE_MESSAGES ." WHERE id = :id";

		$stmt = $connection->prepare($sql);
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);
		$stmt->execute();

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
		$sql = "UPDATE ". TABLE_MESSAGES ." SET seen = 1 WHERE id = :id AND user_id = :user_id LIMIT 1";

		$stmt = $connection->prepare($sql);
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);
		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

		$stmt->execute();
	}

	// marks a message as unread
	public static function msgUnRead($id){
		global $connection;

		$sql = "UPDATE ". TABLE_MESSAGES ." SET seen = 0 WHERE id = :id LIMIT 1";
		$stmt = $connection->prepare($sql);
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);

		if (!$stmt->execute()) {
			return $stmt->errorInfo()[2];
		}

		return true;
	}

	public static function displayMessages($messages, $sec='inbox'){

		$send = $sec == 'sent' ? true : false;

		$html = '';

		if(empty($messages)) {
			$empty = true;
		}

		$html .= "<table class='ui selectable table'>";
		$html .= "<thead>";
		$html .= "<tr>";

		if($send){
		$html .= "<th class='four wide'>To</th>";
		} else {
		$html .= "<th class='four wide'>From</th>";
		}

		$html .= "<th class='eight wide'>Message</th>";

		if($sec == 'archive'){
			$html .= "<th class='two wide unhide'>unHide</th>";
			$html .= "<th style=\"text-align: center;\" class='two wide'>Delete</th>";
		} elseif(!$send) {
			$html .= "<th style=\"text-align: center;\" class='two wide'>Hide</th>";
		}

		$html .= "</tr>";
		$html .= "</thead>";
		$html .= "<tbody class='messages-list'>";

		foreach ($messages as $message):

			if($send){
				$senderID = $message->user_id;
			} else {
				$senderID = $message->sender_id;
			}

			$staff = $message->ual == 1 ? true : false;
			$date = $message->date;
			$subject = $message->subject;
			if (strlen($subject) > 100) $subject = substr($subject, 0, 102)."...";
			$isSeen = self::isSeen($message->id);

			$html .= "<tr class='message-row ";

			if(!$isSeen && !$send) $html .= "unread active";

			if($staff) {
			$html .= " negative";
			}

			$html .= "' id='msgid-$message->id' msg-id='$message->id'";


			$html .= ">";
			$html .= "<td>";
			$html .= "<div class='ui grid'>";
			$html .= "<div class='four wide column'>";
			$html .= "<a href='/sha/user/$senderID/'><img class='ui avatar image' src=' $message->img_path '></a>";
			$html .= "</div>";
			$html .= "<div class='ten wide column'>";
			$html .= "<a class='user-title' user-id='$senderID' href='/sha/user/$senderID'/>$message->u_fullname</a>";
			$html .= "<br><div class='time' id='msg_date' title='$date'>$date</div>";
			$html .="</div>";
			$html .="</td>";
			$html .= "<td class='msg-content selectable'>";
			$html .= "<a style='color:black;text-decoration: none;' href='?msg=$message->id'>";

			$html .= $subject;
			$html .="</a>";

			if($sec == 'archive'){
				$html .="</td>";
				$html .="<td class='msg-remove center aligned'>";
				$html .= "<i class='undo large link icon' id='undo-msg'></i>";
				$html .="</td>";
			}

			if(!$send){
				$html .="<td class='msg-remove center aligned'>";
			
				if($sec == 'archive'){
					$html .= "<i title='Permanently delete message' class='remove large link icon' id='msg_remove'></i>";
				} else {
					$html .= "<i title='Hide message' class='remove large link icon' id='msg_arch'></i>";
				}

				$html .="</td>";
			}
			
			$html .="</tr>";

		endforeach;

		$html .= "</tbody>";
		$html .= "</table>";

		return $html;

	}



}


?>