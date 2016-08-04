<?php
require_once ("classes/init.php");
$pageTitle = "Sign Up";
if($session->is_logged_in()) redirect_to_D('/sha');

?>

<?php
require(ROOT_PATH . 'inc/head.php'); 
?>
<body>
	<div class="main signup-page">
		<div class="container">
			<?= msgs(); ?>
			<div class="ui raised very padded segment sign-up">
				<div class="ui compact warning message">
					<div class="header">
					<i class="warning icon"></i>
						It is recommended to not enter any sensitive or personal information.
					</div>
				</div><hr>

				<div class="ui sign-up form">

					<form class="signup-form" method="POST">

						<div class="field username">
							<label>Username</label>
							<input type="text" name="username" placeholder="Username" />
						</div>

						<div class="field-status username-status">
						</div>
							<p class="form-note">Username will be unique to your account - can be changed later.</p>

						<div class="field">
							<label>E-mail</label>
							<input type="email" name="email" placeholder="E-mail" />
						</div>

						<div class="field-status email-status">
						</div>


						<div class="field">
							<label>Password</label>
							<input type="password" name="password" placeholder="Password" />
						</div>

						<div class="field">
							<label>re-enter Password</label>
							<input type="password" name="repassword" placeholder="Password" />
						</div>

						<br>
						<br>
						 <button class="ui button green submit" type="submit">Sign up</button>
					</form>

				</div>
			</div>
		</div>
	</div>
<script src="/sha/scripts/auth/signup.auth.js"></script>
	<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>










