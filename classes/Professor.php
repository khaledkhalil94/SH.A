<?php
require_once('init.php');

class Professor extends User {
	
	protected static $table_name="professor";
	public $id;
	public $firstName;
	public $lastName;
	public $faculty_id;
	public $bio;
	public $type;


	protected static $db_fields = array('id', 'firstName', 'lastName', 'faculty_id', 'bio', 'type');

	
	public function display_prof ($id){
		$users = self::get_prof_by_faculty($id);

	     foreach ($users as $user) {

	      $output = "";
	        $output .= "<li>";
	        $output .=  "Name: " . $user->firstName . "<br>";
	        $output .=  "ID: " . $user->id . "<br>";
	        $output .=  "Position: " . ucwords($user->type) . "<br>";
	        $output .= "<a href=" . BASE_URL . "students/" . $user->id . "/>View profile</a>";
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

