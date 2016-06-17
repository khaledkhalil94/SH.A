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
            <div class="pagination">

              <?php $rpp = 5;
               Pagination::display(Professor::get_count(), $rpp);
               ?>
            </div>

        <ul class="students">

         <?php
         $profs = Professor::get_users($rpp, $pagination->offset());
        foreach ($profs as $prof) {

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
        <?php Pagination::display(Professor::get_count(), $rpp); ?>
      </div>
    </div>
  </div>
</div>

<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>