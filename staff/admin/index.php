<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");
$pageTitle = "Admin Control Panel";
$session->adminLock();
?>
<body>
  <?php
  include (ROOT_PATH . 'inc/head.php'); 
  ?>

  <div class="main">
    <div class="container section">
      <div class="wrapper">
      <h2>Admin Control Panel</h2>
			</div>
      <a href="<?= "students/students.php" ?>"><h3>Browse all students</h3></a>
      <a href="<?= "staff/professors.php" ?>"><h3>Browse staff</h3></a>
    </div>
  </div>
</div>

<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>