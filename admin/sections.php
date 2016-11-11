<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/src/init.php");
$pageTitle = "Admin Control Panel";

$session->adminLock();

$sections = QNA::get_sections();

$sec = "staff";
$i=0;

if(isset($_POST['submit'])){
	$name = $_POST['name'];
	$acrnm = $_POST['acrnm'];

	Admin::addSection($name, $acrnm);
}

if(isset($_POST['sec_rem']) && ($_POST['sec_rem'] == 'true')){

	$id = $_POST['id'];
	if(Admin::removeSection($id)) die(json_encode('1'));
	else die('0');
}
include (ROOT_PATH . 'inc/head.php');
?>

<body>
	<div class="main" id="admincp">
		<div class="ui container section sec_mng">
			<h2>Sections</h2>
			<button class="ui green button" id="new_sec">Add new section</button><br><br>
			<table class="ui definition selectable single line table">
				<thead>
					<tr>
						<th></th>
						<th>Name</th>
						<th>Acronym</th>
						<th>Questions</th>
						<th class="right aligned">Delete</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($sections as $sec):
					$i++;
					$qs_count = QNA::get_questions_count($sec['id']);
					?>
					<tr id="<?= $sec['id'] ?>">
						<td><?= $i ?></td>
						<td><?= $sec['title'] ?></td>
						<td><?= $sec['acronym'] ?></td>
						<td><?= $qs_count ?></td>
						<?php if($sec['id'] != 1): ?>
						<td class="right aligned"><i class="ui remove icon"></i></td>
						<?php endif; ?>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

		</div>
	</div>
</div>
<?php include (ROOT_PATH . 'inc/footer.php') ?>
</body>
</html>
<div class="ui modal"><div class="content"><form class="ui form new_sec" method="POST"><div class="field"><label>Name</label>
<input type="text" name="name" placeholder="Section name"></div><div class="field"><label>Acronym</label>
<input type="text" name="acrnm" placeholder="Acronym, must be three or less characters"></div>
<button class="ui green button" name="submit" type="submit">Add</button></form></div></div>
<script>
$('#new_sec').click(function(){
	$('.ui.modal').modal('show');
});

$('.ui.remove.icon').click(function(){

	_this = $(this);
	id = _this.closest('tr').attr('id');
	$.post("sections.php", { 'sec_rem': "true", 'id': id},function(data){
		if(data == '1'){
			_this.closest('tr').remove();
		}
	}, 'json')
});
</script>