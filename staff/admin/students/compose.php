<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");
$session->adminLock();
$user_id = $_GET['to'];

$pageTitle = "Send a message";
include (ROOT_PATH . "inc/head.php");

if (isset($_POST['submit'])) {
    if (!empty($_POST['title']) && !empty($_POST['subject'])) {
        Messages::sendMsg();
    } else {
        echo "Please enter a title and a subject.";
    }
    
}
?>

<div class="main">
	<div class="container">
      <div class="form">
        <form action="compose.php?to=<?= $user_id ?>" method="POST">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" value="" />
            </div>
            <div class="form-group">
                <label for="subject">Subject</label>
                <textarea type="text" class="form-control" name="subject" value="" /></textarea >
                <input type="hidden" name="user_id" value="<?= $user_id; ?>" />
                <input type="hidden" name="sender_id" value="<?= $session->user_id; ?>" />
            </div>
            <br>
            <br>
            <!-- <input type="hidden" name="token" value="" /> -->
            <input type="submit" name="submit" value="Send" />
            <a class="btn btn-default" href="<?= "students.php"; ?>" role="button">Cancel</a>

        </form>
      </div>
	</div>
</div>

<?php
include (ROOT_PATH . 'inc/footer.php');
?>