<?php
require_once ("classes/init.php");
$pageTitle = "Log In";
if($session->is_logged_in()){
 header('Location:index.php');
}

if (isset($_POST['submit'])){
	$username = $_POST['id'];
    $password = $_POST['password'];
    //search for the user in the database
    $found_user = Student::authenticate($username, $password);

    if ($found_user) {
    	//success
      $session->login($found_user);
      
      //header('Location:index.php');
  } else {
      // error message
      echo "No user were found";

  }
}


require(ROOT_PATH . 'inc/head.php'); 

  ?>
</pre>
<body>
<?php include (ROOT_PATH . 'inc/header.php') ?>
<?php include (ROOT_PATH . 'inc/navbar.php') ?>
  <div class="main">
    <div class="container">
      <div class="form">
        <form action="login.php" method="post" >
          <div class="form-group">
            <label for="exampleInputEmail1">User ID</label>
            <input type="id" class="form-control" id="exampleInputEmail1" name="id" placeholder="ID">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password">
          </div>
          <button type="submit" name="submit" class="btn btn-default">Log in</button>
        </form>
      </div>
    </div>
  </div>
<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>