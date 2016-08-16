<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/classes/init.php");

$user = new User();
$blocked = $user->get_blocks(USER_ID);

if(!is_array($blocked)){
	die($blocked);
}

?>

<div class="ui segment block-list">
<?php if(empty($blocked)){ ?>
	<p>You don't have anyone in your blocklist.</p>
<?php } else { ?>
<div class="ui middle aligned divided relaxed list">
<?php 
foreach ($blocked as $b):
?>
	<div class="item" user-id="<?= $b->uid; ?>">
		<div class="right floated content">
			<div class="ui basic button" id="user_unblock">unBlock</div>
		</div>
		<img class="ui avatar image" src="<?= $b->path; ?>">
		<div class="content">
			<a href="/sha/user/<?= $b->uid; ?>"><?= $b->full_name; ?></a>
			<br>
			<a class="username" href="/sha/user/<?= $b->uid; ?>">@<?= $b->username; ?></a>
		</div>
	</div>

<?php endforeach; ?>
</div>
<?php } ?>
</div>