<?php 
require_once ($_SERVER["DOCUMENT_ROOT"] . "/sha/classes/init.php");
$faculty = new Faculty();
if(isset($_POST['submit'])){
	$faculty->updateContent($_POST);
}
require_once(ROOT_PATH."faculties/computer_science.php");
?>
 <form id="form" action="" method="post">
    <textarea name="content" id="summernote" rows="10" cols="80">
        <?= Faculty::Display("content", $id); ?>
    </textarea>
    <input type="hidden" name="id" value="2">
    <input type="submit" name="submit" value="sumbit">
</form>

	<script>
	$(document).ready(function() {
	  $('#summernote').summernote();
	});
	</script>
