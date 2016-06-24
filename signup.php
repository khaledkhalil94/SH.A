<?php
require_once ("classes/init.php");
$pageTitle = "Sign Up";
if($session->is_logged_in()){
    header('Location:index.php');
}

if (isset($_POST['submit'])) {
    //if (empty($_POST['username']) || empty($_POST['email'])) {
    if (empty($_POST['username'])) {
        exit("Username and password can't be empty");
    } elseif(!is_numeric($_POST['id'])){
         exit("ID must be number");
    } else {
        if($user = StudentInfo::create_student()){
            $session->login($user);
            StudentInfo::log("signup", $user);
            $session->message("Thanks for signing up, please update your information", BASE_URL."students/".$user->id."/");
        }
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