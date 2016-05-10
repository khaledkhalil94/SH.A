<?php
require_once('init.php');

class Professor extends User {
	
	protected static $table_name="professor";
	public $id, $firstName, $lastName, $faculty_id, $bio, $type, $has_pic;

	protected static $db_fields = array('id', 'firstName', 'lastName', 'faculty_id', 'bio', 'type');

	
	public function display_prof($id){
		global $ProfilePicture;
		$users = self::get_prof_by_faculty($id);
	     foreach ($users as $user) {
			$img_path = $ProfilePicture->get_profile_pic($user);
	      	$output = "";
	        $output .= "<li>";
         	$output .= "<div class=\"row\">";
            $output .= "<div class=\"col-md-2\">";
            $output .= "<div class=\"image\"><img src=" . $img_path ." style=\"width:120px;\"></div>";
            $output .= "</div>";
            $output .= "<div class=\"col-md-6\">";
	        $output .=  "Name: " . $user->firstName . "<br>";
	        $output .=  "ID: " . $user->id . "<br>";
	        $output .=  "Position: " . ucwords($user->type) . "<br>";
	        $output .= "<a href=" . BASE_URL . "staff/professor.php?id=" . $user->id . ">View profile</a>";
            $output .= "</div>";
            $output .= "</div>";
	        $output .=  "</li>";
	      echo $output;
	  	}
	}

	private function get_prof_by_faculty($id){
		global $connection;

		$sql = "SELECT * FROM professor ";
		$sql .= "WHERE faculty_id = {$id}";
		$all = static::find_by_sql($sql);
		return $all;
	}
}

