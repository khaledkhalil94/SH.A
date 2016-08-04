<?php
require_once('init.php');

class Student extends User {
	
	protected static $table_name="students";
	public $firstName,$lastName,$id,$address,$phoneNumber,$faculty_id,$has_pic,$gender,$about,$website,$skype,$twitter,$github,$facebook,

	$error=false, $errMsg;

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

	public static function update_user_privacy($data){
		global $connection;

		// sanitizing the data to be injected safely into the database
		$data = array_map("sanitize_id", $data);

		$array = array_keys($data);
		$set = '';
		foreach ($array as $field) {

			if($field == 'submit') continue;

			if (isset($data[$field])) {
				$set.="`$field`=$data[$field], ";
			}
		}

		$set = substr($set, 0, -2); 


		$sql = "UPDATE `user_privacy` SET {$set} WHERE user_id = ".USER_ID;

		$stmt = $connection->prepare($sql);

		if($stmt->execute()){

			return true;

		} else {

			$error = $stmt->errorInfo();
			return $error[2];

		}
	}


	public function full_name(){
		return $this->firstName . " " . $this->lastName;
	}

	// public static function upload_pic($data) {
	// 	global $Images;
	// 	$Images->table_name = self::$table_name;
	// 	$Images->upload_pic($data);
	// }

	// public static function update_pic($data) {
	// 	global $Images;
	// 	$Images->table_name = self::$table_name;
	// 	$Images->id = $_POST['id'];
	// 	$Images->update_pic($data);
	// }

	// public static function delete_pic() {
	// 	global $Images;
	// 	$Images->table_name = self::$table_name;
	// 	$Images->delete_pic();
	// }

}

$student = new Student();
	// TBR
	// public static function profilePrivacy($user){
	// 	global $session;
	// 	switch($user->profile_privacy){
	// 		case '1': //public
	// 			return true;
	// 			break;			

	// 		case '0': //private
	// 			return $session->userLock($user) ? true : exit("This profile is private!");
	// 			break;			

	// 		case '2': //users only
	// 			return $session->userCheck($user) ? true : "";
	// 			break;
	// 	}
	// }

	// public static function CheckPrivacy($user, $property){
	// 	global $session;
	// 	switch ($property) {
	// 		case '1': //public
	// 			return true;
	// 			break;			

	// 		case '0': //private
	// 			return $session->userCheck($user) ? true : false;
	// 			break;			

	// 		case '2': //users only
	// 			return $session->is_logged_in() ? true : false;
	// 			break;
	// 	}
	// }

	// public function display_students($id){
	// 	global $Images;
	// 	$students = self::get_students_by_faculty($id);
 //         foreach ($students as $student) {
 //              $img_path = $Images->get_profile_pic($student);
 //              $output = "";
 //                $output .= "<li>";
 //                $output .= "<div class=\"row\">";
 //                $output .= "<div class=\"col-md-2\">";
 //                $output .= "<div class=\"image\"><img src=" . $img_path ." style=\"width:120px;\"></div>";
 //                $output .= "</div>";
 //                $output .= "<div class=\"col-md-6\">";
 //                $output .=  "Full name: " . $student->full_name_by_id($student->id) . "<br>";
 //                $output .=  "ID: " . $student->id . "<br>";
 //                $output .= "<a href=" . BASE_URL . "user/" . $student->id . "/>View profile</a>";
 //                $output .= "</div>";
 //                $output .= "</div>";
 //                $output .=  "</li>";
 //              echo $output;
 //         }
	// }

	// public static function get_students_by_faculty($id){
	// 	global $connection;

	// 	$sql = "
	// 			SELECT students.id, students.has_pic, faculties.name
	// 			FROM `students`
	// 			INNER JOIN `faculties` ON students.faculty_id = faculties.id
	// 			WHERE students.faculty_id = {$id}
	// 			";
	// 	$all = static::find_by_sql($sql);
	//  	return $all;
	// }
