<?php 
require_once ($_SERVER["DOCUMENT_ROOT"] . "/src/init.php");
$id = $_POST['uid'];

$user = new User();
$following = $user->get_flwing($id);


?>
<div class="ui three stackable cards">
<?php

foreach ($following as $f): 
	if(!is_object($f)) continue;
	$is_frnd = $user->is_friend($f->id, USER_ID);

	?>
	<div class="card">
			<a class="image" href="/user/<?= $f->id ?>/">
				<img src="<?= $f->img_path ?>">
			</a>
		<div class="content">
			<div class="header"><a href="/user/<?= $f->id ?>/"><?= $f->full_name ?></a>
			<?php if($is_frnd){ ?>
				<i title="You and <?= $f->firstName ?> are friends" class="mdi mdi-account-multiple" style="color: #1ed02d; margin-left:5px;"></i>
			<?php } ?>
			</div>
			<div class="meta">
				<a href="/user/<?= $f->id ?>/">@<?= $f->username ?></a>
			</div>
			<div class="description">
				<?= $f->about ?>
			</div>
		</div>
	</div>
<?php endforeach;
 ?>
 </div><br>


