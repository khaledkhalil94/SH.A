<?php
require_once ("../../src/init.php");

// if (!isset($session->user_id)) {
// 	header("Location: " . BASE_URL . "index.php");
// }
//$id = (int)$session->user_id;
$id = $_GET['id'];

if ($_GET['id'] != $id) {
	header("Location: " . BASE_URL . "users/edituser.php?id=".$id);
}


$userInfo = Staff::find_by_id($id);
$user = Professor::find_by_id($id);

if (isset($_POST['submit'])) {
	    $user->firstName = $_POST['firstName'];
	    $user->lastName = $_POST['lastName'];
	    $user->bio = $_POST['bio'];

	    switch ($_POST['section']) {
	    	case 'Engineering':
	    		$user->section = "1";
	    		break;

	    	case 'Computer Science':
	    		$user->section = "2";
	    		break;    

	    	case 'Medicine':
	    		$user->section = "3";
	    		break;
	    
	    	default:
	    		$user->section = "0";
	    		break;
	    }

	  if($user->update()){

	 	$session->message("Your information have been updated");
	 	//header("Location: " . BASE_URL . "users/".$session->user_id."/");

	 } else {
	 	echo $_SESSION['fail']['sqlerr'];
	 }


}

$section = "users";
$pageTitle = $user->id;
include (ROOT_PATH . "inc/head.php");
include (ROOT_PATH . 'inc/header.php');
include (ROOT_PATH . 'inc/navbar.php');
 ?>
<div class="container section">
<?php if(!empty($session->msg)):?>
<div class="alert alert-success" role="alert"> <?= $session->msg; ?></div>
<?php endif; ?>

<?php 
$Images = new Images($userInfo->type);
$Images->id = $id;

if($user->has_pic){
$img_path = Images::get_profile_pic($id);
} else {
	$img_path = BASE_URL."images/profilepic/pp.png";
}
 ?>

<h2>Update your information</h2>

<br>
	<div class="container">
		<div class="col-md-4">
			
			<?php // if the user has no picture at all
			 if(empty($img_path)){ ?>
			<p>Upload profile picture</p>
	 		<?php	if (isset($_POST['upload'])) {
	 					if (empty($_FILES['userfile']['name'])) {
	 						echo "Please select a valid photo";
	 					} else {
							$Images->upload_pic($id);
							header("Refresh:0");
	 					}
					}
				} else { //use already has a profile picture ?>
				<div class="image"><img src=<?php echo $img_path;?> alt="" style="width:228px;"></div>
				<p>Change your profile picture</p>
		 		<?php
			 		if (isset($_POST['upload'])) {
			 			if (empty($_FILES['userfile']['name'])) {
	 						echo "Please select a valid photo";
	 					} else {
							$Images->update_pic($id);
							header("Refresh:0");
					}
				}
			}
				 ?>
			<form enctype="multipart/form-data" action="<?php echo "updateinfo.php?id=". $id ?>" method="POST">
			    <!-- MAX_FILE_SIZE must precede the file input field -->
			    <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
			    <!-- Name of input element determines name in $_FILES array -->
			    <input name="userfile" type="file" />
			    <input type="submit" name="upload" value="Upload" />
			</form>
			<br>

			<?php if(!empty($img_path)):
			  ?>

				<form action="<?php echo "updateinfo.php?id=". $id ?>" method="POST">
				<?php if (isset($_POST['delete'])) { 
					$Images->delete_pic($id);
					
					    }?>
					<input type="submit" name="delete" class="btn btn-secondary" value="Delete Picture" />
				</form>
			<?php endif; ?>
		</div>

		<div class="col-md-6">
		    <form action="<?php echo "updateinfo.php?id=". $id ?>" method="POST">
				
		        <div class="form-group">
		            <label for="firstName">First Name</label>
		            <input type="firstName" class="form-control" name="firstName" value="<?php echo $user->firstName ?>" />
		        </div>

		        <div class="form-group">
		            <label for="lastName">Last Name</label>
		            <input type="lastName" class="form-control" name="lastName" value="<?php echo $user->lastName ?>" />
		        </div>

		        <div class="form-group">
		            <label for="about">About</label>
		            <textarea class="form-control" name="bio" rows="3"><?php echo $user->bio ?></textarea>
		        </div>

		        <label for="phoneNumber">Select your faculty</label>
		        <select class="form-control" name="section">
				  <option <?php if ($user->section == "1") {echo "selected";} ?>>Engineering</option>
				  <option <?php if ($user->section == "2") {echo "selected";} ?> >Computer Science</option>
				  <option <?php if ($user->section == "3") {echo "selected";} ?>>Medicine</option>
				</select>
				<br>
		        <input type="submit" class="btn btn-primary" name="submit" value="Update" />
		        <a class="btn btn-default" href="<?php echo BASE_URL."staff/professor.php?id=".USER_ID; ?>/" role="button">Cancel</a>
		    </form>
		</div>
    </div>
</div>
<?php
include (ROOT_PATH . 'inc/footer.php');
?>