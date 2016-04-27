<?php
require_once('init.php');

class Professor extends User {
	
	protected static $table_name="professor";
	public $id;
	public $firstName;
	public $lastName;
	public $faculty_id;
	public $bio;


	protected static $db_fields = array('id', 'firstName', 'lastName', 'faculty_id', 'bio');

	

}

