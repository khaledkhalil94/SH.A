<?php
require_once ("classes/init.php");
$pageTitle = "Sign Up";
if($session->is_logged_in()){
    header('Location:index.php');
}
?>

<?php 

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
            StudentInfo::create_student();
            break;

        case 'professor':
            StaffInfo::create_staff();
            break;
        
        default:
            echo "Please select type";
            break;
    }
}

?>

<?php
require(ROOT_PATH . 'inc/head.php'); 
 ?>

</pre>
<body>
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
            <label class="radio-inline">
              <input type="radio" name="type" checked id="inlineRadio2" value="student">I'm a student
            </label>
            <label class="radio-inline">
              <input type="radio" name="type" id="inlineRadio3" value="professor">I'm a professor
            </label>
            <br>
            <br>
            <!-- <input type="hidden" name="token" value="" /> -->
            <input type="submit" name="submit" value="Create" />

        </form>
      </div>
    </div>
  </div>
<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>