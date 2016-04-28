<?php
require_once('init.php');

class Student extends User {
	
	protected static $table_name="students";
	public $firstName;
	public $lastName;
	public $id;
	public $address;
	public $phoneNumber;
	public $faculty_id;
	public $has_pic;


	protected static $db_fields = array('id', 'firstName', 'lastName', 'address', 'phoneNumber', 'faculty_id');

	function __construct(){
	}

	

	public function display_students ($id){
		global $ProfilePicture;
		$students = self::get_students_by_faculty($id);
         foreach ($students as $student) {
              $img_path = ($student->has_pic) ? $ProfilePicture->get_profile_pic($student->id) :  BASE_URL."images/profilepic/pp.png";
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

	public function get_students_by_faculty($id){
		global $connection;

		$sql = "SELECT * FROM students ";
		$sql .= "WHERE faculty_id = {$id}";
		$all = static::find_by_sql($sql);
		return $all;
	}

	public function full_name_by_id($id) {
		$student = Self::find_by_id($id);
		return $student->firstName . " " . $student->lastName;
	}

}

