<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/src/init.php");
$pageTitle = "Admin Control Panel";
$session->adminLock();
?>
<body>
  <?php
  include (ROOT_PATH . 'inc/head.php'); 
  ?>

  <div class="main">
    <div class="ui container section">
      <div class="wrapper">
      <h2>Messages reports</h2>
			</div>
      <?php $reports = messages::getReports(); ?>
            <table class="table table-hover">
        <thead>
          <tr>
            <th class="col-md-0"></th>
            <th class="col-md-3"></th>
            <th class="col-md-10"></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($reports as $report):
          $sender = Student::find_by_id($report->sender_id);
          $img_path = $Images->get_profile_pic($sender);
          $date = displayDate($report->date);
          $time = get_timeago($report->date);
          $subject = $report->subject;
          if (strlen($subject) > 100) $subject = substr($subject, 0, 102)."...";
          $title = empty($report->title) ? "[Untitled]" : $report->title;
          ?>
          <tr>
            <td><div class="image"><img src="<?= $img_path ?>" style="width:55px;"></div></td>
            <td ><ul>
              <li style="list-style:none;"><?= ucfirst($sender->firstName); ?></li>
              <li style="list-style:none;"><div class="time" title="<?= $date; ?>"><?= $time; ?></div></li>
            </ul></td>
            <td>
              <ul>
                <a style="color:black;text-decoration: none;" href="message.php?msg=<?= $report->id?>">
                  <li style="list-style:none;"><b><?= $title;
                   if(!Messages::isSeen($report->id)) echo " <span class=\"label label-success\">New!</span>"; ?></b></li>
                  <li style="list-style:none;"><?= $subject; ?></li>
                </a>

              </ul>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>