<?php
require_once ("classes/init.php");
$pageTitle = "Sign Up";
if($session->is_logged_in()){
    header('Location:index.php');
}
?>

<?php 

if (isset($_POST['submit'])) {

$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$id = $_POST['id'];

    $student = new StudentInfo();
    $student->username = $DatabaseObject->validate_username($username);
    $student->password = $DatabaseObject->validate_password($password);
    $student->email = $email;
    $student->id = $id;
    $student->create_user();
}

?>

<?php
require(ROOT_PATH . 'inc/head.php'); 
 ?>

</pre>
<body>
<?php include (ROOT_PATH . 'inc/header.php') ?>
<?php include (ROOT_PATH . 'inc/navbar.php') ?>
  <div class="main">
    <div class="container">
      <div class="form">
        <form action="signup.php" method="POST">
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

            <!-- <input type="hidden" name="token" value="" /> -->
            <input type="submit" name="submit" value="Create" />

        </form>
      </div>
    </div>
  </div>
<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>