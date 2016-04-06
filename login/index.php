<?php
require_once ("../config/config.php");
 $pageTitle = "Log In";
 include (ROOT_PATH . 'inc/head.php'); 
 ?>
<body>
<?php include (ROOT_PATH . 'inc/header.php') ?>
<?php include (ROOT_PATH . 'inc/navbar.php') ?>
  <div class="main">
    <div class="container">
      <div class="form">
        <form>
          <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
          </div>
          <button type="submit" class="btn btn-default">Log in</button>
        </form>
      </div>
    </div>
        <?php foreach($students as $id => $student){ 

        
         } ?>
  </div>
<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>