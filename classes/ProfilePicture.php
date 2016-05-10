<?php

 class ProfilePicture {

 	public $table_name;
 	public $id;
 	public $type;
 	public $has_pic=false;

	public static function get_profile_pic($user){
		global $connection;
		$sql = "SELECT `path` FROM profile_pic WHERE user_id = {$user->id}";
		$stmt = $connection->query($sql);
		$res = $stmt->fetch()['path'];
		return $img_path = $user->has_pic ? $res :  BASE_URL."images/profilepic/pp.png";

		if(!$stmt){
			echo $sql;
			echo $connection->errorInfo()[2];
		}
	}

	public static function get_pic_info($id){
		global $connection;
		$sql = "SELECT * FROM profile_pic WHERE user_id = {$id}";
		$stmt = $connection->query($sql);
		$res = $stmt->fetch(PDO::FETCH_OBJ);
		return $res;

		if(!$stmt){
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
			$stmt1 = $connection->prepare($sql);

			$sql = "UPDATE {$this->table_name} SET `has_pic` = '1' WHERE `id` = {$this->id}";
			$stmt2 = $connection->prepare($sql);

			if($stmt1->execute()){
				if (!$stmt2->execute()) {
					echo $stmt2->errorInfo()[2];
				}
			} else {
				echo $stmt1->errorInfo()[2];
				
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
		$uploadfile = $uploaddir . $this->id . $fileName;
		$img_path = $_SERVER["DOCUMENT_ROOT"] . $this->get_profile_pic($this);
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
		$path = $this->get_profile_pic($this);
		if (empty($path)) {
			return true;
		} else {
			$img_path = $_SERVER["DOCUMENT_ROOT"] . $this->get_profile_pic($this);
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

 }

$ProfilePicture = new ProfilePicture();
 ?>