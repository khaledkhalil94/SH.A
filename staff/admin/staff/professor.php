<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");
if (isset($_GET['id'])) {
	$id = (int)$_GET["id"];
}
$userInfo = StaffInfo::find_by_id($id);
$user = Professor::find_by_id($userInfo->id);

if($user->has_pic){
	$img_path = ProfilePicture::get_profile_pic($user);
} else {
	$img_path = BASE_URL."images/profilepic/pp.png";
}


$faculty = $user->get_faculty($user->faculty_id);
$faculty = ucwords(str_replace("_", " ", $faculty));

// $department = substr($user->faculty_id,1,2);
// $department = $user->get_faculty($department);
// $department = ucwords(str_replace("_", " ", $department));

if (empty($userInfo)){
	exit("User was not found!");
	//header("Location: " . BASE_URL . "users/");
} elseif(empty($user)){
	$session->message("Please update your information");
	header("Location: " . BASE_URL . "users/settings/edituser.php?id=".$id);
}

$section = "users";
$pageTitle = $user->id;
include (ROOT_PATH . "inc/head.php");
 ?>
<div class="container">
<?php if(!empty($_SESSION['msg'])):?>
<div class="alert alert-success" role="alert"> <?= $_SESSION['msg']; ?></div>
<?php endif; ?>

	<div class="details row">
		<div class="col-md-5">
			<div class="image"><img src="<?php echo $img_path;?>" alt="" style="width:278px;"></div>
		</div>
			<div class="col-md-6">
				<p><?php echo "Name: " . $user->full_name(); ?></p>
				<p><?php echo "ID: " . $user->id; ?></p>
				<p><?php echo "About: " . $user->bio; ?></p>
				<?php if (!empty($faculty)): ?>
					<p><?php echo "Faculty: " . $faculty; ?></p>
				<?php endif ?>
				<?php if (!empty($department)): ?>
					<p><?php echo "Department: " . $department; ?></p>
				<?php endif ?>
				<?php //if (isset($session->user_id)) {
						//if($user->id === $session->user_id){
				 ?>
				<a class="btn btn-default" href="<?php echo BASE_URL."staff/settings/updateinfo.php?id=".$id?>" role="button">Update your information</a>
				<a class="btn btn-default" href="<?php echo BASE_URL."staff/settings/account.php"?>" role="button">Change account settings</a>
				<?php 	//}
					//} ?>
			</div>
	</div>
</div>
<?php
include (ROOT_PATH . 'inc/footer.php');
?>