<?php 
$news = Faculty::get_content("news");
 ?>
<div class="newsticker col-md-5">
	<script type="text/javascript">
		<!--newsticker config-->
		$(function () {
			$(".demo1").bootstrapNews({
				newsPerPage: 6,
				autoplay: true,
				pauseOnHover:true,
				direction: 'up',
				newsTickerInterval: 4000,
				onToDo: function () {
					console.log(this);
				}
			});

		});
	</script>
	<div class="newsticker-content">
		<!--I hate tables!-->
		<div class="panel panel-default">
			<div class="panel-heading">
				<b>News</b>
			</div>
			<div class="panel-body">
				<div class="row">
					<ul class="demo1" style="overflow-y: hidden; height: 240px;">
					<?php foreach ($news as $new) { ?>
						<li class="news-item">
							<table cellpadding="4">
								<tbody>
									<tr>
										<td>
											<i class="fa fa-lg fa-newspaper-o"></i>
										</td>
										<td>
											<a href="/sha/pages/articles.php?id=<?= $new->id ?>"><?= $new->title; ?></a>
										</td>
										<td style="font-size:small;" class="time"> - <?= get_timeAgo($new->created); ?></td>
									</tr>
								</tbody>
							</table>
						</li>
					<?php } ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>