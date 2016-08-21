<?php
require_once ("src/init.php");
$pageTitle = "Home Page";
$sec = "index";
include (ROOT_PATH . "inc/head.php"); 

?>
<body>
  <div class="main">
    <div class="content">

      <?= msgs(); ?>
      <?php include (ROOT_PATH . 'inc/body/carousel.php') ?>
      <div class="row">
        <?php include (ROOT_PATH . 'inc/body/newsticker.php') ?>
        <?php include (ROOT_PATH . 'inc/body/accordion.php') ?>
      </div>
    </div>
  </div>
  <?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>