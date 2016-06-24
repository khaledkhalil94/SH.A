<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");
if (isset($_GET['id'])) {
	$id = (int)$_GET["id"];
}
$staff = Staff::find_by_id($id);

// $faculty = $user->get_faculty($user->faculty_id);
// $faculty = ucwords(str_replace("_", " ", $faculty));


if (empty($staff)){
	exit("User was not found!");
	//header("Location: " . BASE_URL . "users/");
}

$section = "users";
$pageTitle = $staff->id;
include (ROOT_PATH . "inc/head.php");
 ?>
<div class="container">
<?php if(!empty($_SESSION['msg'])):?>
<div class="alert alert-success" role="alert"> <?= $_SESSION['msg']; ?></div>
<?php endif; ?>

	<div class="details row">
		<?php if ($session->userCheck($staff)): ?>
		<a style="float:right;" class="btn btn-default" href="<?= "messages/compose.php?to={$id}"?>" role="button">Send a private message</a>
		<?php endif; ?>
		<div class="col-md-6">
			<p><?php echo "Name: " . $staff->full_name(); ?></p>
			<p><?php echo "ID: " . $staff->id; ?></p>
			<p><?php echo "e-mail: " . $staff->email; ?></p>
			<p><?php echo "position: " . $staff->type; ?></p>
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