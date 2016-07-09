<?php
require_once('init.php');

class Student extends User {
	
	protected static $table_name="students";
	public $firstName,$lastName,$id,$address,$phoneNumber,$faculty_id,$has_pic,$profile_privacy,
			$gender,$email_privacy,$country_privacy,$phoneNumber_privacy,$gender_privacy;
	protected static $db_fields = array();

	public function __construct(){
		global $db_fields;
		self::$db_fields = array_keys((array)$this);
	}

	public function display_students($id){
		global $ProfilePicture;
		$students = self::get_students_by_faculty($id);
         foreach ($students as $student) {
              $img_path = $ProfilePicture->get_profile_pic($student);
              $output = "";
                $output .= "<li>";
                $output .= "<div class=\"row\">";
                $output .= "<div class=\"col-md-2\">";
                $output .= "<div class=\"image\"><img src=" . $img_path ." style=\"width:120px;\"></div>";
                $output .= "</div>";
                $output .= "<div class=\"col-md-6\">";
                $output .=  "Full name: " . $student->full_name_by_id($student->id) . "<br>";
                $output .=  "ID: " . $student->id . "<br>";
                $output .= "<a href=" . BASE_URL . "students/" . $student->id . "/>View profile</a>";
                $output .= "</div>";
                $output .= "</div>";
                $output .=  "</li>";
              echo $output;
         }
	}

	// public function get_students_by_faculty($id){
	// 	global $connection;

	// 	$sql = "SELECT * FROM students ";
	// 	$sql .= "WHERE faculty_id = {$id}";
	// 	$all = static::find_by_sql($sql);
	// 	return $all;
	// }

	public static function get_students_by_faculty($id){
		global $connection;

		$sql = "
				SELECT students.id, students.has_pic, faculties.name
				FROM `students`
				INNER JOIN `faculties` ON students.faculty_id = faculties.id
				WHERE students.faculty_id = {$id}
				";
		$all = static::find_by_sql($sql);
	 	return $all;
	}

	public function full_name_by_id($id) {
		$student = Self::find_by_id($id);
		return $student->firstName . " " . $student->lastName;
	}

	public function full_name() {
		return $this->firstName . " " . $this->lastName;
	}

	public static function upload_pic($data) {
		global $ProfilePicture;
		$ProfilePicture->table_name = self::$table_name;
		$ProfilePicture->upload_pic($data);
	}

	public static function update_pic($data) {
		global $ProfilePicture;
		$ProfilePicture->table_name = self::$table_name;
		$ProfilePicture->id = $_POST['id'];
		$ProfilePicture->update_pic($data);
	}

	public static function delete_pic() {
		global $ProfilePicture;
		$ProfilePicture->table_name = self::$table_name;
		$ProfilePicture->delete_pic();
	}

	public static function profilePrivacy($user){
		global $session;
		switch($user->profile_privacy){
			case '1': //public
				return true;
				break;			

			case '0': //private
				return $session->userLock($user) ? true : exit("This profile is private!");
				break;			

			case '2': //users only
				return $session->userCheck($user) ? true : "";
				break;
		}
	}

	public static function CheckPrivacy($user, $property){
		global $session;
		switch ($property) {
			case '1': //public
				return true;
				break;			

			case '0': //private
				return $session->userCheck($user) ? true : false;
				break;			

			case '2': //users only
				return $session->is_logged_in() ? true : false;
				break;
		}

	}

}

