<?php
require_once ("config/config.php");
$pageTitle = "SHA";
include (ROOT_PATH . "inc/head.php"); 
?>
<body>
  <?php include (ROOT_PATH . 'inc/header.php') ?>
  <?php include (ROOT_PATH . 'inc/navbar.php') ?>
  <div class="main">
    <div class="content">
      <?php include (ROOT_PATH . 'inc/body/carousel.php') ?>
      <?php include (ROOT_PATH . 'inc/body/faculties.php') ?>
      <div class="row">
        <?php include (ROOT_PATH . 'inc/body/newsticker.php') ?>
        <?php include (ROOT_PATH . 'inc/body/accordion.php') ?>
      </div>
    </div>
  </div>
  <?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>