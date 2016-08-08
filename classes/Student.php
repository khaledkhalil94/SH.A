<?php
require_once('init.php');

class Student extends User {
	
	protected static $table_name="students";
	//public $firstName,$lastName,$id,$address,$phoneNumber,$faculty_id,$has_pic,$gender,$about,$website,$skype,$twitter,$github,$facebook,

	public $error=false, $errMsg;

	protected static $db_fields = array();

	public function __construct(){
		global $db_fields;
		self::$db_fields = array_keys((array)$this);
	}

	public function get_user_info($id){
		global $connection;

		$sql = "SELECT students.*, CONCAT(students.firstName, ' ', students.lastName) AS full_name, info.username, info.email, info.register_date AS joined, privacy.*, pic.path AS img_path
				FROM `students`
				RIGHT JOIN `login_info` AS info ON students.id = info.id 
				INNER JOIN `user_privacy` AS privacy ON students.id = privacy.user_id
				LEFT JOIN `profile_pic` AS pic ON students.id = pic.user_id
				WHERE students.id = {$id} LIMIT 1";


		$stmt = $connection->prepare($sql);

		if(!$stmt->execute()){
			$error = $stmt->errorInfo();
			$this->error = true;
			$errMsg = $error[2];
			return false;
		}

		$obj = $stmt->fetch(PDO::FETCH_OBJ);

		if(empty($obj)){
			$this->error = true;
			$errMsg = "Error fetching user details from the database.";
			return false;
		}

		if(!is_object($obj)){
			$this->error = true;
			$errMsg = $obj;
			return false;
		}

		$obj->img_path = $obj->img_path ?: DEF_PIC; 

		return $obj;
	}

	// public static function update_user_privacy($data){
	// 	global $connection;

	// 	// sanitizing the data to be injected safely into the database
	// 	$data = array_map("sanitize_id", $data);

	// 	$array = array_keys($data);
	// 	$set = '';
	// 	foreach ($array as $field) {

	// 		if($field == 'submit') continue;

	// 		if (isset($data[$field])) {
	// 			$set.="`$field`=$data[$field], ";
	// 		}
	// 	}

	// 	$set = substr($set, 0, -2); 


	// 	$sql = "UPDATE `user_privacy` SET {$set} WHERE user_id = ".USER_ID;

	// 	$stmt = $connection->prepare($sql);

	// 	if($stmt->execute()){

	// 		return true;

	// 	} else {

	// 		$error = $stmt->errorInfo();
	// 		return $error[2];

	// 	}
	// }

}

$student = new Student();
