<?php
require_once ("classes/init.php");
$pageTitle = "Log In";
if($session->is_logged_in()){
 header('Location:index.php');
}

require(ROOT_PATH . 'inc/head.php'); 

	?>
</pre>
<body>
	<div class="main login-page">
		<div class="container">
	<?= msgs(); ?>
		<div class="ui raised very padded segment log-in">
				<div class="ui message">
					<div class="header">
						Log in to your account.
					</div>
				</div>
				<hr id="login-hr">

				<div class="ui login form">
					<form class="login-form" method="post">
						<div class="field username">
							<label>Username</label>
							<input type="text" id="username" name="username" placeholder="Username">
						</div>
						<div class="field username">
							<label>Password</label>
							<input type="password" id="exampleInputPassword1" name="password" placeholder="Password">
						</div>
							<br>
							<button class="ui button green submit" type="submit">Log in</button>
					</form>
				</div>
			</div>
		</div>
	</div>
<script src="/sha/scripts/auth/signin.auth.js"></script>
<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>