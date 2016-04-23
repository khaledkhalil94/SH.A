<?php
require_once('init.php');

class Student extends DatabaseObject {
	
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

	public function get_profile_pic($id){
		global $connection;
		$sql = "SELECT `path` FROM profile_pic WHERE user_id = {$id}";
		$res = $connection->query($sql);
			if($res){
				$img_path = $res->fetch()['path'];
				return $img_path;
			} else {
				echo $sql;
				echo $connection->errorInfo()[2];
		}
	}


	public function upload_pic(){
		global $connection;

		$fileName = basename($_FILES['userfile']['name']);
		$fileTmp  = $_FILES['userfile']['tmp_name'];
		$fileType = $_FILES['userfile']['type'];
		$fileSize = $_FILES['userfile']['size'];

		$uploaddir = ROOT_PATH . 'images/profilepic/';
		$uploadfile = $uploaddir . $fileName;

		if(move_uploaded_file($fileTmp, $uploadfile)){
			//register in the database
			$path = BASE_URL . 'images/profilepic/'.$_FILES['userfile']['name'];
			$sql = "INSERT INTO profile_pic (`user_id`, `path`, `type`, `size`) VALUES ($this->id, '$path','$fileType', '$fileSize')";
			if(!$connection->query($sql)){
				echo $connection->errorInfo()[2];
			}
		}
	}

	public function update_pic(){
		global $connection;

		$fileName = basename($_FILES['userfile']['name']);
		$fileTmp = $_FILES['userfile']['tmp_name'];
		$fileType = $_FILES['userfile']['type'];
		$fileSize = $_FILES['userfile']['size'];
		$uploaddir = ROOT_PATH . 'images/profilepic/';
		$img_path = $_SERVER["DOCUMENT_ROOT"] . $this->get_profile_pic($this->id);
		$uploadfile = $uploaddir . $fileName;
		$path = BASE_URL . 'images/profilepic/'.$_FILES['userfile']['name'];

		$sql = "UPDATE profile_pic SET `path` = '$path', `type` = '$fileType', `size` = '$fileSize' WHERE user_id = {$this->id}";
		$stmt = $connection->query($sql);
		if($stmt) {
			move_uploaded_file($fileTmp, $uploadfile);
			return true;
		} else {
			$error = ($connection->errorInfo());
			echo $sql;
			echo $error[2];
 		} 
	}

	public function delete_pic(){
		global $connection;

		$img_path = $_SERVER["DOCUMENT_ROOT"] . $this->get_profile_pic($this->id);

		if($img_path == DEF_PIC ){
			return false;
			exit;
		}

		unlink($img_path);

		$sql = "UPDATE profile_pic SET `path` = '/sha/images/profilepic/pp.png', `type` = 'image/png', `size` = '5143' WHERE user_id = {$this->id}";
		$stmt = $connection->query($sql);
		if($stmt) {
			//flash
			return true;
		} else {
			$error = ($connection->errorInfo());
			echo $sql;
			echo $error[2];
 		} 
	}


	public function get_faculty($id){
		global $connection;

		$sql = "SELECT name FROM faculties ";
		$sql .= "WHERE id = {$id} ";
		$sql .= "LIMIT 1";

		$stmt = $connection->query($sql)->fetch(PDO::FETCH_ASSOC);
		if($stmt){
			return $stmt['name'];
		}
		if(!$stmt) {
			$error = ($connection->errorInfo());
			echo $error[2];
		}
	}

	public function get_students_by_faculty($id){
		global $connection;

		$sql = "SELECT * FROM students ";
		$sql .= "WHERE faculty_id = {$id}";
		$all = static::find_by_sql($sql);
		return $all;
	}

	public function full_name() {
		//$student = Self::find_by_id($id);
		return $this->firstName . " " . $this->lastName;
	}

	public function full_name_by_id($id) {
		$student = Self::find_by_id($id);
		return $student->firstName . " " . $student->lastName;
	}

}

