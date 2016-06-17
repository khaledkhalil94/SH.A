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
              <?php $rpp = 10;
              Pagination::display(StudentInfo::get_count(), $rpp); ?>
            </div>

			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>ID</th>
						<th>Username</th>
						<th>Full Name</th>
						<th>Faculty</th>
						<th>Last Activtiy</th>
						<th>Profile</th>
					</tr>
				</thead>
         <?php
         $students = Student::get_users($pagination->rpp, $pagination->offset());
         $num = 0;
         foreach ($students as $student) {
         	$num++;
            if (StudentInfo::find_by_id($student->id)) {
              $faculty = Student::get_faculty($student->faculty_id);
              $img_path = $ProfilePicture->get_profile_pic($student);
              $dateAgo = get_timeago(StudentInfo::find_by_id($student->id)->activity);
?>
				<tbody>
					<tr>
						<td><?= $num; ?></td>
						<td><?= $student->id; ?></td>
						<td><?= StudentInfo::find_by_id($student->id)->username; ?></td>
						<td><?= $student->full_name(); ?></td>
						<td><?= $faculty; ?></td>
						<td><?= $dateAgo; ?></td>
						<td><?= "<a href=" .BASE_URL . "staff/admin/students/student.php?id=" . $student->id . ">View profile</a>"; ?></td>
					</tr>
				</tbody>
 <?php       }
         } ?>
			</table>

      <div class="pagination">
        <?php Pagination::display(StudentInfo::get_count(), $rpp); ?>
      </div>
    </div>
  </div>
</div>

<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>