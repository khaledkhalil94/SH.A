	<?php 
	require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php"); ?>
	<div class="navbar-default">
		<div class="container-fluid">
			<!--responsive menu-->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li>
						<a href="<?= BASE_URL; ?>">Home</a>
					</li>
					 <?php if($session->is_logged_in()): ?>
					<li>
				
						<a href="<?= BASE_URL."user/".USER_ID;?>/">Profile</a>

					</li>
					<?php endif; ?>  
					<?php if($session->is_logged_in() && $session->adminCheck()): ?>
						<li>
							<a href="<?= BASE_URL; ?>staff/admin/user/students.php">Students</a>
						</li>
					<li>
						<a href="<?= BASE_URL; ?>staff/admin/staff/professors.php">Staff</a>
					</li> 
					<?php endif ?>
					<li>
						<a href="<?= BASE_URL; ?>pages">News</a>
					</li>
					<li>
						<a href="<?= BASE_URL; ?>questions">Questions</a>
					</li>
					<?php if($session->is_logged_in()): ?>
					<li>
						<a href="<?= BASE_URL; ?>messages">Inbox<?= " <span class=\"label label-danger\">". Messages::getMsgsCount(USER_ID) ."</span>";  ?></a>
					</li>
					<?php endif; ?>  
					<?php if(!$session->is_logged_in()): ?>
					<li>
						<a href="<?= BASE_URL; ?>login.php">Log In</a>
					</li>
					<li>
					<a href="<?= BASE_URL; ?>signup.php">Sign Up</a>
					</li>
					<?php endif; ?>           
					<?php if($session->is_logged_in()): ?>
						<li><a href="<?= BASE_URL; ?>logout.php">Log Out</a></li>
					<?php endif; ?>
					<?php if($session->is_logged_in() && $session->adminCheck()): ?>
					<li>
					<a href="<?= BASE_URL; ?>staff/admin" style="color:red">Admin Control Panel</a>
					</li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</div>