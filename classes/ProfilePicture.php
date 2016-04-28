<?php

 class ProfilePicture {

 	public $table_name;
 	public $id;
 	public $type;

 	function __construct($type="students"){
 		switch ($type) {
 			case 'student':
 				$this->table_name = "students";
 				break;

 			case 'staff':
 				$this->table_name = "staff";
 				break;
 			
 			default:
 				# code...
 				break;
 		}
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
		$uploadfile = $uploaddir . $this->id . $fileName;

		if(move_uploaded_file($fileTmp, $uploadfile)){
			//register in the database
			$path = BASE_URL . 'images/profilepic/'.$this->id.$_FILES['userfile']['name'];
			$sql = "INSERT INTO profile_pic (`user_id`, `path`, `type`, `size`) VALUES ($this->id, '$path','$fileType', '$fileSize')";
			if(!$connection->query($sql)){
				exit($connection->errorInfo()[2]);
			}
			$connection->query("UPDATE {$this->table_name} SET `has_pic` = '1' WHERE `id` = {$this->id}");
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
		$uploadfile = $uploaddir . $this->id . $fileName;
		$path = BASE_URL . 'images/profilepic/'.$this->id.$_FILES['userfile']['name'];

		$sql = "UPDATE profile_pic SET `path` = '$path', `type` = '$fileType', `size` = '$fileSize' WHERE user_id = {$this->id}";
		$stmt = $connection->query($sql);
		if($stmt) {
			unlink($img_path);
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

		$connection->query("UPDATE {$this->table_name} SET `has_pic` = '0' WHERE `id` = {$this->id}");
		$sql = "DELETE FROM `profile_pic` WHERE `user_id` = {$this->id}";
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

 }

$ProfilePicture = new ProfilePicture();
 ?>