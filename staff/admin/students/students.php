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
      <a class="btn btn-default" href="<?= "newuser.php"?>" role="button">Add a new user</a>
      <div class="wrapper">
        <h2>Students list</h2>
<?php
            $rpp = 4; //results per page
            $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
            $pagination = new Pagination($rpp, $current_page, StudentInfo::get_count());
            if ($current_page < 1 || $current_page > $pagination->total_pages()) {
              $pagination->current_page = 1;
            } 
?>
            <div class="pagination">
              <?php include (ROOT_PATH . "inc/navigation.php"); ?>
            </div>
<?php
            $students = StudentInfo::get_users($rpp,$pagination->offset());
            $users=array();
            foreach ($students as $student) {
                $users[] = Student::find_by_id($student->id);
            }
            $student = new Student();
?>
        <ul class="students">

         <?php

         $out = array();
            foreach ($users as $key => $value){
                $out[] = (object)array_merge((array)$students[$key], (array)$value);
            }
            $users = $out;
         foreach ($users as $user) {

            if (Student::find_by_id($user->id)) {
              $faculty = Student::get_faculty($user->faculty_id);
              $faculty = ucwords(str_replace("_", " ", $faculty));
              $img_path = ($user->has_pic) ? $ProfilePicture->get_profile_pic($user->id) :  BASE_URL."images/profilepic/pp.png";
              $output = "";
                $output = $output . "<li>";
                $output = $output . "<div class=\"row\">";
                $output = $output . "<div class=\"col-md-3\">";
                $output = $output . "<div class=\"image\"><img src=" . $img_path ." style=\"width:155px;\"></div>";
                $output = $output . "</div>";
                $output = $output . "<div class=\"col-md-6\">";
                $output = $output .  "Username: " . $user->username . "<br>";
                $output = $output .  "Full name: " . $student->full_name_by_id($user->id) . "<br>";
                $output = $output .  "ID: " . $user->id . "<br>";
                $output = $output .  "Faculty: " . " " . $faculty . "<br><br>";
                $output = $output . "<a href=" .BASE_URL . "staff/admin/students/student.php?id=" . $user->id . ">View profile</a>";
                $output = $output . "</div>";
                $output = $output . "</div>";
                $output = $output .  "</li>";
              echo $output;
            }
         }
              ?>

      </ul>
      <div class="pagination">
        <?php include (ROOT_PATH . "inc/navigation.php"); ?>
      </div>
    </div>
  </div>
</div>

<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>