<?php require_once ("src/init.php");
if(isset($_POST['send'])){
  require(ROOT_PATH . "src/contact.php");
  exit;
}
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
    <h3>
      Or send me a direct message
    </h3>
    <div class="ui raised segment contact">
      <div class="ui middle aligned center aligned grid">
        <div class="column">
          <form class="ui large form" style="text-align:left;" action"contact.php" method="POST">
            <div class="field">
              <label for="name">Name</label>
              <input type="text" name="name" placeholder="Your name..">
            </div>
            <div class="field">
              <label for="email">e-mail <span class="time">(optional)</span></label>
              <input type="text" name="email" placeholder="Your e-mail..">
            </div>
            <div class="field">
              <label for="message">Message</label>
              <textarea rows="3" type="text" name="message"></textarea>
            </div>
            <input type="hidden" name="token" value="<?= Token::generateToken(); ?>">
            <input class="ui fluid large green submit button" name="send" type="submit" value="Send">
            <div class="ui error message"></div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
$(document)
  .ready(function() {
    $('.ui.form')
      .form({
        fields: {
          name: {
            identifier  : 'name',
            rules: [
              {
                type   : 'empty',
                prompt : 'Please enter your name'
              },{
                type   : 'maxLength[40]',
                prompt : 'Name can\'t be longer than 40 characters'
              }
            ]
          },
          email: {
            identifier  : 'email',
            optional: true
          },
          message: {
            identifier  : 'message',
            rules: [
              {
                type   : 'empty',
                prompt : 'Please enter message'
              }
            ]
          }
        }
      })
    ;
  })
;
</script>
<?php include (ROOT_PATH . 'inc/footer.php') ?>
