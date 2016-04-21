<?php
require_once ("../../classes/init.php");
$pageTitle = "Students";


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
        <h2>Computer Science</h2>
        <div class="pagination">
          <?php// include (ROOT_PATH . "inc/navigation.php"); ?>
        </div>
<?php
            $students = Student::get_students_by_faculty(2);

?>
        <ul class="students">
        <h3>Students registered in Computer Science</h3>

         <?php

         foreach ($students as $student) {
            if (Student::find_by_id($student->id)) {


              $output = "";
                $output = $output . "<li>";
                $output = $output .  "Username: " . $student->firstName . "<br>";
                $output = $output .  "ID: " . $student->id . "<br>";
                $output = $output . "<a href=" . BASE_URL . "students/" . $student->id . "/>View profile</a>";
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