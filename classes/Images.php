<?php
require_once('init.php');

 class Images {

	// todo: make $errMsg array and return multiple errors
	public $user_id, $id, $path, $error=false, $errMsg;

	public function __construct(){
		$this->user_id = USER_ID;
	}

	public static function get_profile_pic($user){
		global $connection;
		if (!$user->has_pic) return BASE_URL."images/profilepic/pp.png";

		$sql = "SELECT `path` FROM profile_pic WHERE user_id = {$user->id}";

		$stmt = $connection->query($sql);
		return $stmt->fetch()['path'];


		if(!$stmt){
			echo $sql;
			echo $connection->errorInfo()[2];
		}
	}

	public function get_pic_info($user_id=null){
		global $connection;

		$user_id = $user_id ?: $this->user_id;

		$sql = "SELECT * FROM profile_pic WHERE user_id = {$user_id} LIMIT 1";

		$stmt = $connection->prepare($sql);

		//die($sql);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_OBJ);
		return $res;

		if(!$stmt){
			echo $sql;
			echo $connection->errorInfo()[2];
		}
	}


	// checks if the user has a profile picture or not
	public static function has_pic($user_id){
		global $connection;

		$sql = "SELECT 1 FROM `profile_pic` WHERE user_id = $user_id";

		return $connection->query($sql)->fetch();

	}

	// parse and validate images before upload
	private function process_img($rndmName=false){
		global $imgValidation;


		// if there's an upload error
		if($_FILES['error'] != '0'){

			$this->error = true;

			switch ($_FILES['error']) {
				case '1':			
				case '2':
					$this->errMsg = 'Exceeded filesize limit.';
					break;
				case '3':
					$this->errMsg = 'File did not upload completely.';
					break;
				case '4':
					$this->errMsg = 'No file was uploaded.';
					break;
					
				case '6':
					$this->errMsg = 'Temp folder is missing.';
					break;

				case '7':
					$this->errMsg = 'Failed to write to disk.';
					break;

				case '8':
					$this->errMsg = 'File upload stopped by extension.';
					break;

				default: 
                	$this->errMsg = "Unknown upload error"; 
                	break; 
			}

			return false;
		}
		
		// break the file path into parts
		$path_parts = pathinfo($_FILES['name']);

		$file = [];

		// if true, will use a generated name
		if($rndmName === false) {
			// characters allowed (a-z, A-Z, 0-9, -, _, .)
			$name = $path_parts['filename'];
			$name = trim(strtolower($name));
			$name = str_replace(' ', '', $name);

			if (!(preg_match("`^[-0-9A-Z_\.]+$`i", $name))) {

				$this->error = true;
				$this->errMsg = ('File name is not valid (suspicious characters).');

				return false;

			} elseif((mb_strlen($path_parts['filename'],"UTF-8") > 225)){

				$this->error = true;
				$this->errMsg = ('File name is not valid (too long).');

				return false;

			} else {
		
				$file['name'] = trim($path_parts['filename']);
			} 
		} else {
			$file['name'] = md5(uniqid());
		}


		$file['extension'] = $path_parts['extension'];
		$file['basename'] = $path_parts['basename'];

		$file['tmp_name'] = $_FILES['tmp_name'];
		$file['type'] = $_FILES['type'];
		$file['size'] = $_FILES['size'];


		list($file['width'], $file['height'], $file['typeC'], $file['attr']) = getimagesize($_FILES['tmp_name']);


		// validating the image extension
			if(!in_array($file['typeC'], $imgValidation['allowed_ext_C'])){

				$this->error = true;
				$this->errMsg = ('Extension is not supported.');

				return false;
			}

			if(!in_array($file['extension'], $imgValidation['allowed_ext'])){

				$this->error = true;
				$this->errMsg = ('Extension is not supported.');

				return false;
			}

		// validating image size
			if($file['size'] > $imgValidation['max_size']){

				$this->error = true;
				$this->errMsg = ('Size is too big!');

				return false;
			}

		// validating image dimensions
			if($file['width'] * $file['height'] > $imgValidation['max_width'] * $imgValidation['max_height']){

				$this->error = true;
				$this->errMsg = ('Image dimensions must be lower than 800*800');

				return false;
			}

		return $file;
	}

	public function upload_profile_pic(){
		global $connection;
		
		$file = $this->process_img();

		if (!$file || $this->error) {
			return false;
		}

		// if file does not exist, create it
		if(!file_exists(DEF_IMG_UP_DIR.USER_ID. DS )){
			if(mkdir(DEF_IMG_UP_DIR. DS .USER_ID)){
				
				// create an index file and redirect to 404 page
				$path = DEF_IMG_UP_DIR. DS .USER_ID. DS ;
				$fp = fopen($path . "/index.php", "w");
				fwrite($fp, "<?php header(\"Location: /sha/404.php\"); ?>");
				fclose($fp);

			} else {
				$this->error = true;
				$this->errMsg = ("Could not create user folder.");

				return false;
			}
		}

		$path = DEF_IMG_UP_DIR. DS .USER_ID. DS ;

		// the full path in the server for the to-be-uploaded file
		$upload_dir = $path . $file['name'] .".". $file['extension'];

		if(move_uploaded_file($file['tmp_name'], $upload_dir)){

			//register in the database
			$path = DEF_PIC_PATH . USER_ID . "/" . $file['name'] .".". $file['extension'];

			$this->path = $path;

			$sql = "INSERT INTO `profile_pic` 
			(`user_id`, `path`, `type`, `size`, `name`, `extension`, `width`, `height`, `attr`, `type_constant`) VALUES 
			('{$this->user_id}', '{$path}','{$file['type']}', '{$file['size']}', '{$file['name']}', '{$file['extension']}',
			'{$file['width']}','{$file['height']}', '{$file['attr']}', '{$file['typeC']}')";


			$stmt = $connection->prepare($sql);

			if(!$stmt->execute()){

				$this->error = true;
				$this->errMsg = $stmt->errorInfo()[2];

				return false;

			}

			$this->id = $connection->lastInsertId();

			return true; // you made it!! :)

		} else {

			$this->error = true;
			$this->errMsg = ("Error moving the file.");
			return false;
		}
	}

	public function change_profile_pic(){
		//global $connection;


		// $file = $this->process_img();

		// if (!$file || $this->error) {
		// 	return false;
		// }


		// $oldFile = $this->get_pic_info();

		// if($oldFile->user_id !== USER_ID) {
		// 	$this->error = true;
		// 	$this->errMsg = "Authentication error!";

		// 	return false;
		// }

		// $oldPath = $_SERVER["DOCUMENT_ROOT"] . $oldFile->path;

		// if(!unlink($oldPath)) return false;

		// $path = DEF_IMG_UP_DIR. DS .USER_ID. DS ;
		// $upload_dir = $path . $file['name'] .".". $file['extension'];

		// if(move_uploaded_file($file['tmp_name'], $upload_dir)){

		// 	//register in the database
		// 	$this->path = DEF_PIC_PATH . USER_ID . "/" . $file['name'] .".". $file['extension'];

		// 	$sql = "UPDATE profile_pic SET 
		// 			`path` = '$this->path', `type` = '{$file['type']}', `size` = '{$file['size']}', `name` = '{$file['name']}', `extension` = '{$file['extension']}',
		// 			`width` = '{$file['width']}', `height` = '{$file['height']}', `attr` = '{$file['attr']}', `type_constant` = '{$file['typeC']}'
		// 			WHERE user_id = {$this->user_id}
		// 			LIMIT 1";

		// 	$stmt = $connection->query($sql);

		// 	if(!$stmt) {

		// 		$this->error = true;
		// 		$this->errMsg = $connection->errorInfo();

		// 		return false;
		// 	}
		// }

		// 	return true;

		// so after I wrote this method, I realised that I can just do this..
		if($this->process_img()){

			$this->delete_profile_pic();
			return $this->upload_profile_pic();
		}
	}

	public function delete_profile_pic(){
		global $connection;

		// if user doesn't have pp
		if(!$this->get_pic_info()) {
			$this->error = true;
			$this->errMsg = "Picture was not found!";

			return false;
		}
		
		$oldFile = $this->get_pic_info();

		if($oldFile->user_id !== USER_ID) {
			$this->error = true;
			$this->errMsg = "Authentication error!";

			return false;
		}
		
		$oldPath = $_SERVER["DOCUMENT_ROOT"] . $oldFile->path;

		$sql = "DELETE FROM `profile_pic` WHERE `user_id` = {$this->user_id} LIMIT 1";
		$stmt = $connection->query($sql);
		if($stmt) {

			unlink($oldPath);
			return true;

		} else {
			$error = $connection->errorInfo();
			
			$this->error = true;
			$this->errMsg = $error;

			return false;
		} 

	}

 }

 ?>