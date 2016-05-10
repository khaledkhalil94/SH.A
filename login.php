<?php
require_once ("classes/init.php");
$pageTitle = "Log In";
if($session->is_logged_in()){
 header('Location:index.php');
}

if (isset($_POST['submit'])){
	  $username = trim(strtolower($_POST['Username']));
    $password = trim(strtolower($_POST['password']));
    
    if($username == "admin"){
      $found_user = StaffInfo::authenticate($username, $password);
      } else {
        $found_user = StudentInfo::authenticate($username, $password);
      }

    if ($found_user) {
    	//success
      $StudentInfo->log("login", $found_user);
      $session->login($found_user);
      $session->message('You have logged in.', "/sha");
    } else {
        // error message
        echo "No user were found";

    }
}


require(ROOT_PATH . 'inc/head.php'); 

  ?>
</pre>
<body>
  <div class="main">
    <div class="container">
      <div class="form">
        <form action="login.php" method="post" >
          <div class="form-group">
            <label for="Username">Username</label>
            <input type="id" class="form-control" id="Username" name="Username" placeholder="ID">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password">
          </div>
            <br>
          <button type="submit" name="submit" class="btn btn-default">Log in</button>
        </form>
      </div>
    </div>
  </div>
<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>