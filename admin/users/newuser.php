<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/src/init.php");
$session->adminLock();


$pageTitle = "Create a new user";
$sec = "users";
include (ROOT_PATH . "inc/head.php");

if (isset($_POST['submit'])) {
    if (empty($_POST['username'])) {
        exit('Put in a username');
    }
    if(!is_numeric($_POST['id'])){
        echo "ID must be number";
        exit;
    }
    switch ($_POST['type']) {
        case 'student':
            if($user = StudentInfo::create_student()){
                $session->message("User has been created.", "..");
            }
            break;
        case 'staff':
            Staff::create_staff();
            break;
        
        default:
            echo "Please select type";
            break;
    }
}

?>

<div class="main">
	<div class="container">
      <div class="form">
        <form action="newuser.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" value="" />
            </div>
            <div class="form-group">
                <label for="id">ID</label>
                <input type="text" class="form-control" name="id" value="" />
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" value="" />
            </div>
            <div class="form-group">
                <label for="email">email</label>
                <input type="email" class="form-control" name="email" value="" />
            </div>
            <label class="radio-inline">
              <input type="radio" name="type" checked id="inlineRadio2" value="student">Student
            </label>
            <label class="radio-inline">
              <input type="radio" name="type" id="inlineRadio3" value="staff">Staff
            </label>
            <br>
            <br>
            <!-- <input type="hidden" name="token" value="" /> -->
            <input type="submit" name="submit" value="Create" />
            <a class="btn btn-default" href="<?= "students.php"; ?>" role="button">Cancel</a>

        </form>
      </div>
	</div>
</div>

<?php
include (ROOT_PATH . 'inc/footer.php');
?>