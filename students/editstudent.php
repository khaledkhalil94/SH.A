<?php
require_once ("../classes/init.php");
if (isset($_GET['id'])) {
	$id = intval($_GET["id"]);
}

$studentInfo = StudentInfo::find_by_id($id);
$student = Student::find_by_id($studentInfo->id);


if (empty($student)){
	echo "User was not found!";
	//header("Location: " . BASE_URL . "students/");
	exit;
}

if (isset($_POST['submit'])) {

    $studentInfo->username = $_POST['username'];
    $studentInfo->email = $_POST['email'];
    $studentInfo->id = $id;
    if($studentInfo->update()){
    	echo "username updated";
    }
}

$section = "students";
$pageTitle = $student->id;
include (ROOT_PATH . "inc/head.php");
include (ROOT_PATH . 'inc/header.php');
include (ROOT_PATH . 'inc/navbar.php');
 ?>
<div class="container">
<?php echo "Username: " . $studentInfo->username . "<br>";?>
<?php 
if (!empty($studentInfo->email)){
	echo "E-Mail: " . $studentInfo->email . "<br>";
} else {
	echo "User has not set an E-Mail! <br>";
}
 ?>

    <form action="<?php echo "editstudent.php?id=". $id ?>" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" value="<?php echo $studentInfo->username ?>"/>
        </div>
        <div class="form-group">
            <label for="email">email</label>
            <input type="email" class="form-control" name="email" value="<?php echo $studentInfo->email ?>" />
        </div>

        <input type="submit" name="submit" value="Create" />

    </form>
</div>
<?php
include (ROOT_PATH . 'inc/footer.php');
?>