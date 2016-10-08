<?php
require_once('init.php');

 class Images {

	public $user_id;
  public $name;
  public $ext;
  public $id;
  public $path;
  public $error=false;
  public $errMsg;

	public function __construct(){
		$this->user_id = USER_ID;
	}

	public static function get_profile_pic($user){
		global $connection;

		if (!$user->has_pic) return BASE_URL."images/profilepic/pp.png";

		$sql = "SELECT ". TABLE_PROFILE_PICS ." FROM profile_pic WHERE user_id = {$user->id}";

		$stmt = $connection->query($sql);
		return $stmt->fetch()['path'];


		if(!$stmt){
			return $connection->errorInfo()[2];
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
		$database = new Database();

		return $database->row_exists('profile_pic', 'user_id', $user_id);

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

		// characters allowed (a-z, A-Z, 0-9, -, _, .)
		$name = $path_parts['filename'];
		$name = trim(strtolower($name));
		$name = str_replace(' ', '', $name);

		if (!(preg_match("`^[-0-9A-Z()$^&!_\.]+$`i", $name))) {

      $this->name = md5(uniqid());

		} elseif((mb_strlen($path_parts['filename'],"UTF-8") > 225)){

			$this->error = true;
			$this->errMsg = ('File name is not valid (too long).');

			return false;

  	} else {

			$this->name = trim($path_parts['filename']);
		}


		$file['extension'] = strtolower($path_parts['extension']);
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

      $this->ext = $file['extension'];

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

    $path = DEF_IMG_UP_DIR. DS .USER_ID. DS;
    $t_path = $path . DS . 'thumbnails';

		// if file does not exist, create it
		if(!file_exists($path)){
			if(mkdir($path)){
        mkdir($t_path);

				// create an index file and redirect to 404 page
				$fp = fopen($path . "/index.php", "w");
				fwrite($fp, "<?php header(\"Location: /sha/404.php\"); ?>");
				fclose($fp);

			} else {
				$this->error = true;
				$this->errMsg = ("Could not create user folder.");

				return false;
			}
		}

    $extension = htmlentities($file['extension']);

		// the full path in the server for the to-be-uploaded file
		$upload_dir = $path . $this->name .".". $extension;

		if(move_uploaded_file($file['tmp_name'], $upload_dir)){

			//register in the database
			$path = DEF_PIC_PATH . USER_ID . "/" . $this->name .".". $file['extension'];

      $this->resize();

      $t_path = DEF_PIC_PATH . USER_ID . '/thumbnails/' . $this->name.'.'.$this->ext;

      $data = [
        'user_id' => $this->user_id,
        'path' => $path,
        'thumb_path' => $t_path,
        'type' => $file['type'],
        'size' => $file['size'],
        'name' => $this->name,
        'extension' => $this->ext,
        'width' => $file['width'],
        'height' => $file['height'],
        'attr' => $file['attr'],
        'type_constant' => $file['typeC']
      ];

      $database = new Database();

      $insert = $database->insert_data(TABLE_PROFILE_PICS, $data);

      if($insert === true){

        $this->id = $database->lastId;
        $this->path = $path;

        return true;

      } else {

        $this->error = true;
        $this->errMsg = array_shift($database->errors);
        return false;
      }

		} else {

			$this->error = true;
			$this->errMsg = ("Error moving the file.");
			return false;
		}
	}

	public function change_profile_pic(){
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

		$sql = "DELETE FROM ". TABLE_PROFILE_PICS ." WHERE `user_id` = {$this->user_id} LIMIT 1";
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

  private function resize(){

    require('SimpleImage.php');

    $path = DEF_IMG_UP_DIR. USER_ID. DS . $this->name.'.'.$this->ext;

    $t_path = DEF_IMG_UP_DIR .USER_ID . '/thumbnails';

    if(!is_dir($t_path)){
        mkdir($t_path);
      }

    $t_path = DEF_IMG_UP_DIR. USER_ID. '/thumbnails/' . $this->name.'.'.$this->ext;

    $img = new abeautifulsite\SimpleImage($path);
    $img->thumbnail(120, 120)->save($t_path);

  }
 }

 ?>
