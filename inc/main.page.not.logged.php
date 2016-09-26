<h1>Welcome!</h1>
<h4>You can read more about this project here.<br>If you have any suggestions or find any bugs, feel free to open up an issue on the github page.</h4>
<p>In the meanwhile, you can login below, or <a href="/sha/signup.php"><b>register</b></a> a new account if you haven't signed up yet!</p>

<div class="main login-page">
	<div class="ui segment log-in">
		<div class="ui login form">
			<form class="login-form" method="post">
					<input type="text" id="username" autocomplete="off" name="username" placeholder="Username or ID">
				<div class="field username">
				</div>
					<input type="password" name="password" placeholder="Password">
				<div class="field password">
				</div>
					<br>
					<button class="ui button blue basic submit" type="submit">Log in</button>
				<input type="hidden" name="auth_token" value="<?= Token::generateToken(); ?>" />
			</form>
		</div>
	</div>
</div>
<script src="/sha/scripts/auth.js"></script>