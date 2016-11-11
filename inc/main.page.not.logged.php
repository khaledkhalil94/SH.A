<h1>Welcome to SH.A!</h1>
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
<p>If you haven't signed up yet, you can<a href="/signup.php"><b> register</b></a> a new account.</p>
<h3>See the <a href="https://github.com/khaledkhalil94/SH.A"><i class="ui icon github"></i>github</a> page for features and more information about this project.<br>
If you have any suggestions or find any bugs, feel free to open up <a href="https://github.com/khaledkhalil94/SH.A/issues">a new issue</a>.</h3>
<script src="/scripts/auth.js"></script>
