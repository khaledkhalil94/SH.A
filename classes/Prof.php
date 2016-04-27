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


	protected static $db_fields = array('firstName', 'lastName', 'address', 'phoneNumber', 'faculty_id');

	function __construct(){
	}

	

}

