<div class="ui two column grid">
	<div class="twelve wide column">
		<div class="blog-post" id="<?= $id; ?>">
			<div class="ui grid post-header">
				<div class="two wide column post-avatar">
					<div class="thumbnail small">
						<a href="<?= BASE_URL.'user/'.$q->uid ?>/"><img src="<?= $q->img_path ?>"></a>
					</div>
				</div>
				<div class="nine wide column post-title">
					<h3><a href="/sha/user/<?= $q->uid; ?>/"><?= $q->full_name;?></a></h3>				
					<p class="time"><span id="post-date" title="<?=$post_date;?>"><?= $post_date;?></span>  in <a href="/sha/questions/?section=<?= $q->acr; ?>"><?= $q->fac; ?></a> <?= $edited; ?></p>
				</div>
			</div>
			<?php if($session->adminCheck() && $reports_count) { ?>
				<div class="report-message">
					<div class="ui negative compact message reports">
						<a style="color:red;" href="/sha/admin/report.php?id=<?= $id; ?>">This question has been reported <?= $reports_count; ?></a>
					</div>
				</div>
			<?php } ?>
			<br>
			<div class="ui left aligned" style="min-height:320px;">
				<?php if($q->status == "2"){ ?>
				<div class="ui warning message">
					This question is private, only you can see it, you can change that by clicking <a id="post-publish" href="#"> here.</a> 
				</div>
				<?php } ?>
				<div class="ui header">
					<h3 class="blog-post-title"><?= $q->title; ?></h3>
					<!-- Question actions menu -->
					<div title="Actions" class="ui pointing dropdown" id="blog-post-actions">
						<i class="setting link large icon"></i>
						<div class="menu">
							<?php if ($session->is_logged_in() && !$self) { ?>
							<div class="item" id="post_save">
								<a class="ui a">Save Question</a>
							</div>
							<div class="item" id="post_report">
								<a class="ui a">Report</a>
							</div>
							<?php } ?>

							<?php if ($session->userCheck($user) || $session->adminCheck()): ?>
							<div class="item" id="post-edit">
								<a class="ui a">Edit</a>
							</div>
							<?php if($q->status == "1"){ ?>
							<div class="item" id="post-unpublish">
								<a class="ui a">Hide</a>
							</div>
							<?php } else { ?>
							<div class="item" id="post-publish">
								<a class="ui a">unHide</a>
							</div>
							<?php } ?>
							<div class="item" id="post-delete">
								<a class="ui a">Delete</a>
							</div>

							<?php endif; ?>	
						</div>
					</div>
					<!-- Question actions menu -->
				</div>
				<div class="ui divider"></div>
				<p><?= $q->content; ?></p>
			</div>
			<hr><br>
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
			</div>
		</div>
	</div>
	<div class="four wide column" style="border-left: 1px #e2e2e2 solid;">
		<div class="sidebar-module sidebar-module-inset">
			<h4>Related questions</h4>
			<div class="ui segment">
				<div class="ui relaxed divided list" id="sidebar-content">
					<?php 
						$items = QNA::get_posts_by_section($q->section, 5, true);
						if(count($items) < 2) echo "<p>There are no other question in this section.</p>";
							else;
						foreach(QNA::get_posts_by_section($q->section, 5, true) as $item){ ?>
						<?php if ($q->id == $item->id) continue; ?>
							<div class="item">
								<div class="content">
									<a href="question.php?id=<?= $item->id; ?>"><?= $item->title; ?></a>
								</div>
								<span id="sidebar-date"><?= $item->created; ?></span>
							</div>
					<?php } ?>
				</div>
			</div>
			<h4>More questions by <a href="/sha/user/<?= $q->uid; ?>/"><?= $q->full_name; ?></a></h4>
			<div class="ui segment">
				<div class="ui relaxed divided list" id="sidebar-content">
					<?php
					$items = QNA::get_posts_by_user($q->uid, 5, true);
					if (count($items) < 2) echo "<p>This user doesn't have any other questions.</p>";
						else;
					 foreach($items as $item){ ?>
					<?php if ($q->id == $item->id) continue; ?>
						<div class="item">
							<div class="content">
								<a href="question.php?id=<?= $item->id; ?>"><?= $item->title; ?></a>
							</div>
							<span id="sidebar-date"><?= $item->created; ?></span>
						</div>
					<?php 
						}  ?>
				</div>
			</div>
		</div>
	</div>
</div>