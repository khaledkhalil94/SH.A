<?php
// TO BE REMOVED
// require_once('init.php');

// class Staff extends User {
	
// 	protected static $table_name="staff";
// 	public $id, $username, $password, $email, $type="staff", $firstName, $lastName, $department_id, $has_pic;
// 	protected static $db_fields = array();

// 	public function __construct(){
// 		global $db_fields;
// 		self::$db_fields = array_keys((array)$this);
// 	}

// 	public static function create_staff(){
// 	public static function create_staff(){
// 		global $connection;
// 		global $session;

// 		$user = self::create_user();

// 		//$sql = "INSERT INTO staff (`id`) VALUES ('{$user->id}')" ;
// 		// $stmt = $connection->prepare($sql);

// 		// if($stmt->execute()){
// 		// 	$session->login($user);
// 		//  	$session->message("Thanks for signing up, please update your information");
// 		// 	 	//header("Location:".BASE_URL."professors/".$user->id."/");

// 		// } else {
// 		// 	echo "err<br>";
// 		// 	$error = $stmt->errorInfo();
// 		// 	echo $error[2];
// 		// }

// 	}

// 	public function display_prof($id){
// 		global $ProfilePicture;
// 		$users = self::get_prof_by_faculty($id);
// 	     foreach ($users as $user) {
// 			// $img_path = $ProfilePicture->get_profile_pic($user);
// 	      	$output = "";
// 	        $output .= "<li>";
//          	$output .= "<div class=\"row\">";
//             // $output .= "<div class=\"col-md-2\">";
//             // $output .= "<div class=\"image\"><img src=" . $img_path ." style=\"width:120px;\"></div>";
//             // $output .= "</div>";
//             $output .= "<div class=\"col-md-6\">";
// 	        $output .=  "Name: " . $user->firstName . "<br>";
// 	        $output .=  "ID: " . $user->id . "<br>";
// 	        $output .=  "Position: " . ucwords($user->type) . "<br>";
// 	        $output .= "<a href=" . BASE_URL . "staff/professor.php?id=" . $user->id . ">View profile</a>";
//             $output .= "</div>";
//             $output .= "</div>";
// 	        $output .=  "</li>";
// 	      echo $output;
// 	  	}
// 	}

// 	private function get_prof_by_faculty($id){
// 		global $connection;

// 		$sql = "SELECT * FROM staff ";
// 		$sql .= "WHERE department_id = {$id}";
// 		$all = static::find_by_sql($sql);
// 		return $all;
// 	}

// 	public function full_name() {
// 		return $this->firstName . " " . $this->lastName;
// 	}

// }

	function insert_benef_fact_data($denumire, $cui, $adresa, $cont_bancar, $data_emiterii, $serie, $numar) {
		global $dbh;
		$stmt = $dbh ->prepare("INSERT INTO facturi (benef_denumire, benef_cui, benef_adresa, benef_cont_bancar, data_emiterii, serie, numar)
				VALUES(:benef_denum,:benef_cui,:benef_adresa, :benef_cont_bancar, :data_emiterii, :serie, :numar)");
		$stmt -> bindParam(':benef_denum', $denumire);
		$stmt -> bindParam(':benef_cui', $cui);
		$stmt -> bindParam(':benef_adresa', $adresa);
		$stmt -> bindParam(':cont_bancar', $cont_bancar);
		$stmt -> bindParam(':data_emiterii', $data_emiterii);
		$stmt -> bindParam(':serie', $serie);
		$stmt -> bindParam(':numar', $numar);
		$stmt -> execute();
		echo "New records inserted successfully";
		$stmt ->close();
	}

	function insert_benef_fact_data($denumire, $cui, $adresa, $cont_bancar, $data_emiterii, $serie, $numar) {
		global $dbh;
		$sql = "INSERT INTO `facturi` ('benef_denumire', 'benef_cui', 'benef_adresa', 'benef_cont_bancar', 'data_emiterii', 'serie', 'nr')
		VALUES(:benef_denum, :cui, :adresa, :cont_bancar, :data_emiterii, :serie, :numar)";

		$stmt = $dbh->prepare($sql);
		
		$stmt = $dbh->prepare("INSERT INTO `facturi` ('benef_denumire', 'benef_cui', 'benef_adresa', 'benef_cont_bancar', 'data_emiterii', 'serie', 'nr')
		VALUES(:benef_denum, :cui, :adresa, :cont_bancar, :data_emiterii, :serie, :numar)");

		$stmt -> bindParam(':benef_denum', $denumire);
		$stmt -> bindParam(':cui', $cui);
		$stmt -> bindParam(':adresa', $adresa);
		$stmt -> bindParam(':cont_bancar', $cont_bancar);
		$stmt -> bindParam(':data_emiterii', $data_emiterii);
		$stmt -> bindParam(':serie', $serie);
		$stmt -> bindParam(':numar', $numar);
		if(!$stmt->execute()){
			$error = $stmt->errorInfo();
			exit($error[2]);
		} else {
			echo "New records inserted successfully";
			return true;
		}
	}

	function insert_benef_fact_data() {
		global $dbh;

		$sql = "INSERT INTO `facturi` (`benef_denumire`, `benef_cui`, `benef_adresa`, `benef_cont_bancar`, `data_emiterii`, `serie`, `numar`)
				VALUES('benef_denum','benef_cui','benef_adresa', 'benef_cont_bancar', 'data_emiterii', 'serie', 'numar')";

		$stmt = $dbh ->prepare($sql);
		if(!$stmt->execute()){
			$error = $stmt->errorInfo();
			exit($error[2]);
		}
	}

	$stmt = $dbh ->prepare("INSERT INTO facturi (benef_denumire, benef_cui, benef_adresa, benef_cont_bancar, data_emiterii, serie, numar)
				VALUES('benef_denum','benef_cui','benef_adresa', 'benef_cont_bancar', 'data_emiterii', 'serie', 'numar')");