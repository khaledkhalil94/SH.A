<?php
require_once ("../classes/init.php");
$pageTitle = "Students";


// $students_per_page = 4;

// if (empty($_GET["pg"])){
//   $current_page = 1;
// } else {
//   $current_page = $_GET["pg"];
// }

// $current_page = intval($current_page);
// $total_students = all_count();

// $total_pages = ceil($total_students / $students_per_page);
// $next_page = $current_page + 1;
// $prev_page = $current_page - 1;


// if ($current_page > $total_pages){
//   header ("Location: ./?pg=" . $total_pages);
// }

// if ($current_page < 1 ){
//   header ("Location: ./");
// }

// $start = (($current_page - 1) * $students_per_page) + 1;
// $end = $current_page * $students_per_page;
// if ($end > $total_students){
//   $end = $total_students;
// }

// $students = get_students_subset($start, $end);
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
        <h2>Students list</h2>
        <div class="pagination">
          <?php// include (ROOT_PATH . "inc/navigation.php"); ?>
        </div>
<?php
            $students = StudentInfo::find_all_students();
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
              $output = "";
                $output = $output . "<li>";
                $output = $output .  "name: " . $user->username . "<br>";
                
                $output = $output .  "full name: " . $student->full_name_by_id($user->id) . "<br>";
                $output = $output .  "ID: " . $user->id . "<br>";
                if(isset($user->address)){
                    $output = $output .  "address:" . " " . $user->address . "<br>";
                }
                $output = $output .  "Full Page:" . " " . "<a href=" . BASE_URL . "students/" . $user->id . '/' . ">Details</a>";
                $output = $output .  "</li>";
                echo $output;
            }
         }
        //  $position = 0;
        //  $display = "";
        //  $displayCount = 5;
        //  foreach($students as $student){
        //   $position = $position + 1;
        //   if ($position <= $displayCount) {
        //     $display = $display . displayHTML($student);
        //   }

        // }
        // echo $display;
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