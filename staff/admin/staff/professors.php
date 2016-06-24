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
               Pagination::display(Staff::get_count(), $rpp);
               ?>
            </div>

        <ul class="students">

         <?php
         $profs = Staff::get_users($rpp, $pagination->offset());
        foreach ($profs as $prof) {
          $faculty = Staff::get_faculty($prof->department_id);
          $faculty = ucwords(str_replace("_", " ", $faculty));
          $output = "";
            $output = $output . "<li>";
            $output = $output .  "name: " . $prof->firstName . "<br>";
            $output = $output .  "ID: " . $prof->id . "<br>";
            $output = $output . "Position: " . $prof->type . "<br>";
            $output = $output . "Department: " . $faculty . "<br>";
            $output = $output . "Profile: <a href=professor.php?id=" . $prof->id . ">View profile</a>";
            $output = $output .  "</li>";
            echo $output;
         }

        ?>
      </ul>
      <div class="pagination">
        <?php Pagination::display(Staff::get_count(), $rpp); ?>
      </div>
    </div>
  </div>
</div>

<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>