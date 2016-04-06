<?php
 require_once ("../config/config.php");
 include (ROOT_PATH . 'students/info.php');
 $students = get_students_all();
 $pageTitle = "Students";
if (!isset($_GET["value"])){
	$_GET["value"] = 4;
}
if (empty($_GET["value"])){
	$students_per_page = 4;
} else {
	$students_per_page = $_GET["value"];
}
 if (empty($_GET["pg"])){
  $current_page = 1;
  } else {
    $current_page = $_GET["pg"];
  }

  $current_page = intval($current_page);
  $total_students = all_count();

  $total_pages = ceil($total_students / $students_per_page);
  $next_page = $current_page + 1;
  $prev_page = $current_page - 1;


  if ($current_page > $total_pages){
    header ("Location: ./?pg=" . $total_pages);
  }

  if ($current_page < 1 ){
    header ("Location: ./");
  }

  $start = (($current_page - 1) * $students_per_page) + 1;
  $end = $current_page * $students_per_page;
  if ($end > $total_students){
    $end = $total_students;
  }

  $students = get_students_subset($start, $end);
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
    <?php print_r($_GET["value"]); ?>
		<form method="get" action="">
		    <select name="value">
		        <option VALUE="1" <?php if ($students_per_page == 1){ echo 'selected="selected"'; } ?>>1</option>
		        <option VALUE="2" <?php if ($students_per_page == 2){ echo 'selected="selected"'; } ?>>2</option>
		        <option VALUE="3" <?php if ($students_per_page == 3){ echo 'selected="selected"'; } ?>>3</option>
		        <option VALUE="4" <?php if ($students_per_page == 4){ echo 'selected="selected"'; } ?>>4</option>
		    </select>
		    <INPUT TYPE="submit" name="" />
		</form>
      <h2>Students list</h2>

      <div class="pagination">
        <?php include (ROOT_PATH . "inc/navigation.php"); ?>
      </div>

      <ul class="students">

         <?php
         $position = 0;
         $display = "";
         $displayCount = 5;
         foreach($students as $student){
          $position = $position + 1;
          if ($position <= $displayCount) {
            $display = $display . displayHTML($student);
          }

         }
         echo $display;
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
</html>*/