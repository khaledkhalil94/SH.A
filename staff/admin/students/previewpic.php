<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");
$session->adminLock();
$id = isset($_GET['id']) ? $_GET['id'] : null;

if(!$id){
	Redirect::redirectTo("/sha", 2);
}

$student = Student::find_by_id($id);

$img = Images::get_pic_info($id);

if(!$img){
	exit("User has not set a profile picture.");
} else {
	$img_path = $img->path;
}
 ?>
<?php
$section = "students";
$pageTitle = $student->id;
include (ROOT_PATH . "inc/head.php");

 ?>
<div class="container section">
<?= msgs(); ?>

	<div class="jumbotron">
		<p><?= "Name: " . $student->full_name(); ?></p>
		<p><?= "ID: " . $student->id; ?></p>
	</div>

	<div class="details row">
		<div class="col-md-7">
			<div class="image"><img src="<?= $img_path;?>" alt="" style="max-width:478px;"></div>
		</div>
			<div class="col-md-5">
				<p><?= "Picture Name: " . substr(basename($img->path),strlen($id)); ?></p>
				<p><?= "Picture type: " . $img->type; ?></p>
				<p><?= "Picture size: " . human_filesize($img->size); ?></p>
				<form action="<?php echo "editUserInformation.php?id=". $id ?>" method="POST">
					<input type="submit" name="delete" class="btn btn-danger" value="Delete Picture" />
					<a class="btn btn-default" href="<?= "editUserInformation.php?id=".$id; ?>" role="button">Go back</a>
				</form>
			</div>
	</div>

</div>
<?php
include (ROOT_PATH . 'inc/footer.php');
?>