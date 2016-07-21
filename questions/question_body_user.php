<div class="ui two column grid">
	<div class="twelve wide column">
		<div class="blog-post" id="<?= $id; ?>">
			<div class="ui grid">
				<div class="two wide column">
					<a href="/sha/students/<?= $user->id; ?>/"><img class="ui avatar tiny image" src="/sha\images\profilepic/ag.jpg"></a>
				</div>
				<div class="nine wide column">
					<h3><a href="/sha/students/<?= $user->id; ?>/"><?= $name;?></a></h3>
					
					<p class="time"><span id="post-date" title="<?=$post_date;?>"><?= $post_date;?></span id="post-date-ago"><?= $edited; ?>
				</div>
			</div>
			<?php if($session->adminCheck()) {?>
			<a style="color:red;" href="/sha/staff/admin/questions/report.php?id=<?= $id; ?>"><?= $reports_count; ?></a>
			<?php } ?>
			<br><br>
			<div class="ui left aligned container" style="min-height:320px;">
				<div class="ui header">
					<h3 class="blog-post-title"><?= $q->title; ?></h3>
				</div>
				<div class="ui divider"></div>
				<p><?= $q->content; ?></p>
			</div>


			<div class="actions">
		<?php if($voted){ ?>
		<div class="ui labeled button" tabindex="0">
			<div class="ui red button voted" id="votebtn">
				<i class="heart icon"></i><span>unLike</span>
			</div>
			<a class="ui basic red left pointing label" id="votescount"><?= $votes_count; ?></a>
		</div>
		<?php } else {?>
		<div class="ui labeled button" tabindex="0">
			<div class="ui grey button" id="votebtn">
				<i class="heart icon"></i><span>Like</span>
			</div>
			<a class="ui basic grey left pointing label" id="votescount"><?= $votes_count; ?></a>
		</div>
		<?php } ?>
		<?php if ($session->is_logged_in() && !$session->userCheck($user)) { ?>
			<a type="button" id="post_report" class="ui yellow button">Report</a>
		<?php } ?>
		<?php if ($session->userCheck($user) || $session->adminCheck()) { 
				if ($session->adminCheck()) { ?>
					<a type="button" href="/sha/staff/admin/questions/edit.php?id=<?= $id; ?>" class="ui yellow button">Edit</a>
		<?php	} else { ?>
				<a type="button" class="ui yellow button" id="post-edit">Edit</a>
		<?php	} ?>
				<a type="button" id="post-delete" class="ui red button">Delete</a>
		<?php } ?>	
			</div>
		</div>
	</div>
	<div class="four wide column" style="border-left: 1px #e2e2e2 solid;">
		<div class="sidebar-module sidebar-module-inset">
			<h4>Related questions</h4>
			<?= QNA::sidebar_content($q) ?: "There's nothing here :("; ?>
		</div>
	</div>
</div>
<div></div>
	<script src="scripts/ajax.js"></script>