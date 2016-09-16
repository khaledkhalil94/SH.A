<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/src/init.php");
$pageTitle = "Admin Control Panel";
$session->adminLock();
$delCount = Admin::get_del_count();
$q_reports = QNA::reports("questions");
$c_reports = QNA::reports("comments");

// echo "<pre>";
// print_r(QNA::get_reports("", 10652));
// exit;

?>
<body>
  <?php
  $sec = "users";
  include (ROOT_PATH . 'inc/head.php'); 
  ?>

  <div class="main">
    <div class="ui container section">
      <div class="wrapper">
      <h2>Admin Control Panel</h2>
			</div>
      <a href="<?= "user/students.php" ?>"><h3>Browse all students</h3></a>
      <h3><a href="<?= "questions" ?>">Browse questions.</a></h3>
      <h3><a href="pages/articles.php?display=del"><?= "{$delCount} articles marked for deletion."; ?></a></h3>
      <h3><a href="questions/reports.php"><?= count($c_reports)." comment reported." ?></a></h3>
      <h3><a href="questions/?display=rep"><?= count($q_reports)." questions reported." ?></a></h3>
      
    </div>
  </div>
</div>

<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>