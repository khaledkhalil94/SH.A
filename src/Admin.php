<?php 
require_once('init.php');
/**
* The admin class
* have priviliges to add/edit/remove any object in the databases
*/
class Admin extends User {

	function __construct() {
		global $session;
		$session->adminLock();
	}

	// todo: make the user class do this instead.
	public function create(){
		global $connection;
		$id = intval($_POST['id']);

		$user = self::create_user();
	}	


	public static function delete($user){
		global $connection;
		global $Images;
		$connection->query("DELETE FROM login_info WHERE id = {$user->id} LIMIT 1");
		$connection->query("DELETE FROM students WHERE id = {$user->id} LIMIT 1");
		$Images->id = $user->id;
		$Images->table_name = "profile_pic";
		$Images->delete_pic();
		return true;

	}

	public static function getUserLogsCount($id){
		global $connection;
		$sql = "SELECT count(*) FROM `logs` WHERE user_id = {$id}";
		$res = $connection->query($sql);
		return $res->fetch()[0];

	}

	public static function getUserLogs($id){
		global $connection;
		$sql = "SELECT * FROM `logs` WHERE user_id = {$id}";
		$stmt = $connection->query($sql);
		$array = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$array[] = $row;
		}
		return $array;

	}

	public static function get_logs($id, $rpp, $offset){
		global $connection;
		$sql = "SELECT * FROM `logs` WHERE user_id = {$id} LIMIT {$rpp} OFFSET {$offset}";
		$stmt = $connection->query($sql);
		$array = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$array[] = $row;
		}
		return $array;
		// return Parent::find_by_sql($sql);
	}

	public static function deletelog($id){
		global $connection;
		$sql = "DELETE FROM `logs` WHERE id = {$id} LIMIT 1";
		$connection->query($sql);

	}

	public static function deletelogs($id){
		global $connection;
		$sql = "DELETE FROM `logs` WHERE user_id = {$id}";
		$connection->query($sql);

	}

	public static function get_articles($dp="", $order="created desc"){
		global $connection;
		$sql = "SELECT * FROM `content` ";
			if (!empty($dp)) {
				$sql .= "WHERE status = {$dp} AND ";
			} else {
				$sql .= "WHERE ";
			}
		$sql .=	" type != 'main' ";
		switch ($order) {
			case 'date':
				$sql .= "ORDER BY created DESC";
				break;
			case 'status':
				$sql .= "ORDER BY status ASC";
				break;
			case 'author':
				$sql .= "ORDER BY author ASC";
				break;
			
			default:
				$sql .= "ORDER BY created DESC";
				break;
		}
		$stmt = $connection->query($sql);
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public static function get_del_articles(){
		global $connection;
		$sql = "SELECT * FROM `content` 
				WHERE status = 3
				ORDER BY created
				";
		$stmt = $connection->prepare($sql);
		if(!$stmt->execute()){
			echo $stmt->errorInfo()[2];
		}
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public static function get_del_count(){
		global $connection;
		$sql = "SELECT count(*) FROM `content` 
				WHERE status = 3
				";
		$res = $connection->query($sql);
		return $res->fetch()[0];

	}

	public static function getQuestions($dis="", $order){
		global $connection;
		$sql = "SELECT * FROM `questions` ";
		if ($dis === "rep") {
			$sql .= "WHERE report = 1 ";
		} elseif(!empty($dis) || $dis===0){
			$sql .= "WHERE status = {$dis} ";
		}
		switch ($order) {
			case 'date':
				$sql .= "ORDER BY created DESC";
				break;
			case 'status':
				$sql .= "ORDER BY status DESC";
				break;
			case 'author':
				$sql .= "GROUP BY uid";
				break;
			
			default:
				$sql .= "ORDER BY created DESC";
				break;
		}
		
		//exit($sql);
		$stmt = $connection->query($sql);
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public static function reports($table, $id=""){
		global $connection;

		$sql = "SELECT DISTINCT reports.post_id FROM reports
				INNER JOIN `{$table}` ON reports.post_id = {$table}.id ";
		if (!empty($id)) {
			$sql .= "WHERE post_id = {$id}";
			return $connection->query($sql)->fetch();
		}
		$array = array();
		$stmt = $connection->prepare($sql);
		$stmt->execute();
		while ($row = $stmt->fetch()) {
			$array[] = $row[0];
		}
		return $array;
	}	

}

?>