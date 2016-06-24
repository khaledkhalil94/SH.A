<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");
$session->adminLock();
$id = $_GET['id'];
$user = StudentInfo::find_by_id($id);
$pageTitle = $user->id;
include (ROOT_PATH . "inc/head.php");
if (isset($_POST['submit'])){
	$admin = Staff::authenticate("admin", $_POST['password']);
	if($admin){
		if(Admin::delete($user)){
			$session->message("{$user->username} has been deleted", "students");
		}
	}
}
?>

<div class="main">
	<div class="container">
	<?php if(isset($msg)){
	 	echo $msg."<br><br>";
	 	echo "<a href='students.php'>&laquo; Go back</a>";
	 	} else { ?>
		<div class="form">
			<form action="deleteuser.php?id=<?= $id ?>" method="post" >
				<div class="form-group">
					<label for="password">Please enter your password to confirm the action:</label>
					<input type="password" class="form-control" name="password" placeholder="Password">
				</div>

				<button type="submit" name="submit" class="btn btn-default">Confirm</button>
			</form>
		</div>
		<?php } ?>
	</div>
</div>

<?php
include (ROOT_PATH . 'inc/footer.php');
?>