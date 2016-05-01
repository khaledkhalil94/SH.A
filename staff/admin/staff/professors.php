<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");
$pageTitle = "Professors";

?>
<body>
  <?php
  include (ROOT_PATH . 'inc/head.php'); 
  ?>

  <div class="main">
    <div class="container section">
      <div class="wrapper">
        <h2>PROFS</h2>
<?php
            $rpp = 4; //results per page
            $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pagination = new Pagination($rpp, $current_page, Professor::get_count());
            if ($current_page < 1 || $current_page > $pagination->total_pages()) {
              $pagination->current_page = 1;
            } 
?>
            <div class="pagination">
              <?php include (ROOT_PATH . "inc/navigation.php"); ?>
            </div>
<?php
            $profsid = Professor::get_users($rpp,$pagination->offset());

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
                $output = $output . "Profile: <a href=professor.php?id=" . $prof->id . ">View profile</a>";
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