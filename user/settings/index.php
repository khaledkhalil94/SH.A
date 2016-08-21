<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/src/init.php");
$session->is_logged_in() ? true : Redirect::redirectTo("/sha/signup.php");
$id = $session->user_id;


$user = Student::get_user_info($id);
$session->userLock($user);

$st = isset($_GET['st']) ? $_GET['st'] : 'ui';
$pageTitle = 'Settings';
include (ROOT_PATH . "inc/head.php");
?>
<div class="container section user-settings">
	<?= msgs(); ?>

	<div class="ui grid">
		<div class="five wide column settings-list">
			<div class="ui segment">
				<div class="ui divided list">
					<a href="?st=ui">
						<div class="item <?= $st == 'ui' ? 'active' : null; ?>">Update Information</div>
					</a>
					<a href="?st=li">
						<div class="item <?= $st == 'li' ? 'active' : null; ?>">Update Links</div>
					</a>
					<a href="?st=up">
						<div class="item <?= $st == 'up' ? 'active' : null; ?>">Update Privacy</div>
					</a>
					<a href="?st=us">
						<div class="item <?= $st == 'us' ? 'active' : null; ?>">Change Settings</div>
					</a>
					<a href="?st=dl">
						<div style="background-color:#980505; color:white;" class="item <?= $st == 'dl' ? 'active' : null; ?>">Delete account</div>
					</a>
				</div>
			</div>
		</div>
		<div class="ten wide column settings-content">
			<?php switch ($st) {
				case 'ui':
					require('../inc/editUserInformation.php');
					break;
				case 'up':
					require('../inc/editprivacy.php');
					break;
				case 'us':
					require('../inc/account.php');
					break;
				case 'li':
					require('../inc/editlinks.php');
					break;
				case 'dl':
					require('../inc/delete.php');
					break;
				
				default:
					require('../inc/editUserInformation.php');
					break;
			} ?>
		</div>
	</div>
</div>
<script src="../scripts.js"></script>
<?php
include (ROOT_PATH . 'inc/footer.php');
?>