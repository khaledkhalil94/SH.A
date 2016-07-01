<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");
$pageTitle = "Admin Control Panel";
$session->adminLock();
$allCount = Faculty::articles_count();
$delCount = Admin::get_del_count();
$pubCount = Faculty::pub_count();
$unPubCount = $allCount - $pubCount;
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
      <a href="<?= "messages/reports.php" ?>"><h3>Browse messages reports</h3></a>
      <h3><?= "There are {$delCount} articles marked for deletion and waiting your approval." ?><a href="<?= "cms/articles.php?display=del" ?>">..Browse</a></h3>
      <h3><?= "or browse all articles." ?><a href="<?= "cms/articles.php" ?>">..Browse</a></h3>
      
    </div>
  </div>
</div>

<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>