<?php
require_once ("../classes/init.php");
$pageTitle = "Professors";

?>
<body>
  <?php
  include (ROOT_PATH . 'inc/head.php'); 
  include (ROOT_PATH . 'inc/header.php');
  include (ROOT_PATH . 'inc/navbar.php');
  ?>

  <div class="main">
    <div class="container section">
      <div class="wrapper">
        <h2>PROFS</h2>
        <div class="pagination">
          <?php// include (ROOT_PATH . "inc/navigation.php"); ?>
        </div>
<?php
            $profsid = Professor::get_all_users();

?>
        <ul class="students">

         <?php

        foreach ($profsid as $prof) {

            if (StaffInfo::find_by_id($prof->id)) {
              $faculty = StaffInfo::get_faculty($prof->faculty_id);
              $faculty = ucwords(str_replace("_", " ", $faculty));
              $output = "";
                $output = $output . "<li>";
                $output = $output .  "name: " . $prof->firstName . "<br>";
                $output = $output .  "ID: " . $prof->id . "<br>";
                $output = $output .  "Faculty: " . " " . $faculty . "<br>";
                $output = $output . "Position: " . $prof->type . "<br>";
                $output = $output . "Profile: <a href=" . BASE_URL . "staff/professor.php?id=" . $prof->id . ">View profile</a>";
                $output = $output .  "</li>";
                echo $output;
            }
         }

        ?>
      </ul>
      <div class="pagination">
        <?php //include (ROOT_PATH . "inc/navigation.php"); ?>
      </div>
    </div>
  </div>
</div>

<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>