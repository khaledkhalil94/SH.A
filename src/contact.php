<?php require_once ("src/init.php");
if(!Token::validateToken($_POST['token'])) Redirect::redirectTo();
Token::generateToken(true);
Admin::PrivateMsg();
require(ROOT_PATH . "inc/head.php");
?>
<div class="ui container" id="contact">
  <div class="contact-content">
    <h1>Contact me</h1>
    <div class="contact-list">
      <ul>
      <li><a href="skype:khaledkhalil94?chat"><i class="ui icon link blue big skype"></i></a></li>
      <li><a href='mailto:k.khalil.94@gmail.com' target='_top'><i class="ui icon link violet big mail"></i></a></li>
      <li></li>
    </ul>
    </div>
    <hr>
    <div class="ui raised padded segment contact">
      <h3>Thanks for your message!</h3>
    </div>
  </div>
</div>
<?php
include (ROOT_PATH . 'inc/footer.php') ?>
