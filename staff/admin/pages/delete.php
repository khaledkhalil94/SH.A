<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/classes/init.php");
include (ROOT_PATH . 'inc/head.php');
$session->adminLock();
$id = $_GET['id'];
if(!Faculty::find_by_id($id)) $session->message("Page was not found!", "/sha/404.php");
if (isset($_POST['submit'])){
	$admin = Staff::authenticate("admin", $_POST['password']);
	if($admin){
		if (Faculty::delete($id)) {
			$session->message("Article has been deleted!", "./articles.php", "success");
		} else {
			$session->message("Something is wrong!", ".", "danger");
		}	
	} else {
		$session->message("Password is wrong!", "", "danger");
	}
}

?>
<body>
	<div class="container section">
		<?= msgs(); ?>
		<div class="main">
			<div class="container">
				<div class="form">
					<form action="delete.php?id=<?= $id ?>" method="post" >
						<div class="form-group">
							<label for="password">Please enter your password to confirm the action:</label>
							<input type="password" class="form-control" name="password" placeholder="Password">
						</div>
						<button type="submit" name="submit" class="btn btn-success">Confirm</button>
						<a type="button" href="articles.php?id=<?= $id ?>" class="btn btn-default">Cancel</a>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>