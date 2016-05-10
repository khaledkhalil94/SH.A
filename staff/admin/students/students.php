<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");
$pageTitle = "Students";
$session->adminLock();

?>
<body>
  <?php
  require(ROOT_PATH . 'inc/head.php'); 
  ?>

  <div class="main">
    <div class="container section">
  <?= msgs(); ?>
      <a class="btn btn-default" href="<?= "newuser.php"?>" role="button">Add a new user</a>
      <div class="wrapper">
        <h2>Students list</h2>
            <div class="pagination">
              <?php StudentInfo::displayPag(); ?>
            </div>

        <ul class="students">

         <?php
         $students = Student::get_users($rpp, $pagination->offset());
         foreach ($students as $student) {
            if (StudentInfo::find_by_id($student->id)) {
              $faculty = Student::get_faculty($student->faculty_id);
              $img_path = $ProfilePicture->get_profile_pic($student);
              $output = "";
                $output = $output . "<li>";
                $output = $output . "<div class=\"row\">";
                $output = $output . "<div class=\"col-md-3\">";
                $output = $output . "<div class=\"image\"><img src=" . $img_path ." style=\"width:155px;\"></div>";
                $output = $output . "</div>";
                $output = $output . "<div class=\"col-md-6\">";
                $output = $output .  "Username: " . StudentInfo::find_by_id($student->id)->username . "<br>";
                $output = $output .  "Full name: " . $student->full_name() . "<br>";
                $output = $output .  "ID: " . $student->id . "<br>";
                $output = $output .  "Faculty: " . " " . $faculty . "<br><br>";
                $output = $output . "<a href=" .BASE_URL . "staff/admin/students/student.php?id=" . $student->id . ">View profile</a>";
                $output = $output . "</div>";
                $output = $output . "</div>";
                $output = $output .  "</li>";
              echo $output;
            }
         }
              ?>

      </ul>
      <div class="pagination">
        <?php StudentInfo::displayPag(); ?>
      </div>
    </div>
  </div>
</div>

<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>