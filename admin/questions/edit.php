<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/src/init.php");
include (ROOT_PATH . 'inc/head.php');
$session->adminLock();
$id = isset($_GET['id']) ? $_GET['id'] : null;
$q = QNA::find_by_id($id)  ?: $session->message("Page was not found!", "/sha/404.php", "danger");
if(isset($_POST['submit'])){
	if (!empty(trim($_POST['title'])) && !empty(trim($_POST['content']))) {
		if ($QNA->update($_POST)){
			$session->message("Question has been updated successfully!", ".", "success");
		} else {
			$session->message("Something went wrong!", ".", "danger");
		}
	} else {
		$session->message("Title and content can't be empty!", "", "danger");
	}
}
?>
<body>
	<div class="ui container section">
	<?= msgs(); ?>
	<h3>Edit Page</h3>
		<div class="row">
			<div class="col-md-8">
				<form action="" method="POST">
					<div class="form-group">
						<label for="title">Title</label>
						<input type="hidden" name="id" value="<?php echo $q->id ?>" />
						<input type="title" name="title" class="form-control" id="title" value="<?= $q->title; ?>" placeholder="Title">
					</div>
					<div class="form-group">
						<label for="content">Content</label>
						<textarea class="form-control" name="content" rows="7"><?= $q->content; ?></textarea>
					</div>
					<input type="hidden" name="author" class="form-control" value="staff" >
					<br>
					<p><b>Change Status</b></p>
					<div class="radio">
						<label>
						<input <?= ($q->status == "1") ? "checked" : null; ?> type="radio" name="status" value="1">
							Public 
						</label>
						<label>
						<input <?= ($q->status == "0") ? "checked" : null; ?> type="radio" name="status" value="0">
							Private
						</label>
					</div>
					<br>
					<button type="submit" name="submit" class="btn btn-success">Submit</button>
					<a type="button" href="." class="btn btn-default">Cancel</a>
				</form>
			</div>
			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">Info</div>
					<div class="panel-body">
					<?php $author = Student::find_by_id($q->uid) ?: Staff::find_by_id($q->uid); ?>
						<p><b>Page created by: </b><a href="/sha/user/<?= $author->id; ?>/"><?= $author->full_name(); ?></a></p>
						<p><b>Created on: </b><?= displayDate($q->created, "d-m-Y")." at ".displayDate($q->created, "H:i"); ?></p>
						<p><b>Last modified on: </b><?= displayDate($q->last_modified, "d-m-Y")." at ".displayDate($q->last_modified, "H:i"); ?> (<?= get_timeago($q->last_modified); ?>)</p>
						<p><b>Current status: </b><?= $q->status ? $greenIcon : $redIcon; ?></p>
						<hr>
						<a type="button" href="delete.php?id=<?= $id; ?>" class="btn btn-danger">Delete page</a>
					</div>
				</div> 
			</div>
		</div>
	</div>
	</div>
<?php include (ROOT_PATH . 'inc/footer.php'); ?>
</body>
</html>
