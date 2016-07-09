<?php
require_once ("classes/init.php");
$pageTitle = "Sign Up";
if($session->is_logged_in()) header('Location:index.php');


if (isset($_POST['submit'])) {
    if (empty($_POST['username'])) {
        exit("Username and password can't be empty");
    } else {
        $_POST['id'] = mt_rand(10000,20000);
        if($user = StudentInfo::create_student()){
            $session->login($user);
            //StudentInfo::log("signup", $user);
            redirect_to_D("welcome.php");
            require_once("welcome.php");
            exit;
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
    <?= msgs(); ?>
      <div class="form">
        <form action="signup.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" value="" />
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