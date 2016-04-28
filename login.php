<?php
require_once ("classes/init.php");
$pageTitle = "Log In";
if($session->is_logged_in()){
 header('Location:index.php');
}

if (isset($_POST['submit'])){
	  $username = $_POST['Username'];
    $password = $_POST['password'];
    //search for the user in the database
    switch ($_POST['type']) {
    case 'student':
       $found_user = StudentInfo::authenticate($username, $password);
        break;

    case 'professor':
        $found_user = StaffInfo::authenticate($username, $password);
        break;
    
    default:
        echo "Please select type";
        break;
    }
    

    if ($found_user) {
    	//success
      $session->login($found_user);
      header('Location:index.php');
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
            <label for="Username">Username</label>
            <input type="id" class="form-control" id="Username" name="Username" placeholder="ID">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password">
          </div>
          <label class="radio-inline">
            <input type="radio" name="type" checked id="inlineRadio2" value="student">I'm a student
          </label>
          <label class="radio-inline">
            <input type="radio" name="type" id="inlineRadio3" value="professor">I'm a professor
          </label>
            <br>
            <br>
          <button type="submit" name="submit" class="btn btn-default">Log in</button>
        </form>
      </div>
    </div>
  </div>
<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>