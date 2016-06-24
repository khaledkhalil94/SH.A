<?php
require_once ("../classes/init.php");
$pageTitle = "Professors";

?>
<body>
  <?php
  include (ROOT_PATH . 'inc/head.php'); 
  ?>
<?php
    $admin = Staff::find_by_id(1);

?>
  <div class="main">
    <div class="container section">
      <div class="wrapper">
      <h2>Staff area</h2>
	      <div class="row">
			  <div class="col-md-4"><h3>Admins</h3><?php echo $admin->username; ?></div>
			  <div class="col-md-4"><h3>Professors</h3></div>
			  <div class="col-md-4"><h3>Assistant teachers</h3></div>
			</div>

    </div>
  </div>
</div>

<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>