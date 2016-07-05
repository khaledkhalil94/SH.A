<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");
$pageTitle = "Admin Control Panel";
$session->adminLock();
$allCount = Faculty::articles_count();
$delCount = Admin::get_del_count();
$pubCount = Faculty::pub_count();
$unPubCount = $allCount - $pubCount;
$q_reports = QNA::reports("questions");
$c_reports = QNA::reports("comments");

// echo "<pre>";
// print_r(QNA::get_reports("", 10652));
// exit;

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
      <h3><a href="<?= "questions" ?>">Browse questions.</a></h3>
      <h3><a href="<?= "pages/articles.php" ?>">Browse all articles.</a></h3>
      <h3><?= "{$delCount} articles marked for deletion." ?><a href="<?= "pages/articles.php?display=del" ?>">..Browse</a></h3>
      <h3><?= count($c_reports)." comment reported." ?><a href="<?= "questions/reports.php" ?>">..Browse</a></h3>
      <h3><?= count($q_reports)." questions reported." ?><a href="<?= "questions" ?>">..Browse</a></h3>
      
    </div>
  </div>
</div>

<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>