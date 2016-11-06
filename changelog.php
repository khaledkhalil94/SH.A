<?php
require_once ("src/init.php");
$pageTitle = "Changes";
include (ROOT_PATH . "inc/head.php");
?>
<div class="ui container section>">
  <div class="github">
    <table class="ui celled striped table">
      <thead>
        <tr><th colspan="3">
          Latest Commits
        </th>
      </tr></thead>
      <tbody id="gh-body">
      </tbody>
    </table>
  </div>
</div>
<?php include (ROOT_PATH . 'inc/footer.php') ?>
</script>
<script>
	var row = '';
	var count=0;

	$.getJSON('https://api.github.com/repos/khaledkhalil94/sh.a/commits', function(data) {
		for(var i in data){
			count++;
			var date = data[i].commit.committer.date;
      var msg = data[i].commit.message;

			row += "<tr><td class='collapsing'>";
			row += "<i class='github icon'></i><a href='"+ data[i].html_url +"'>"+ msg +"</a></td>";
			row += "<td class='aligned collapsing' title='"+ date +"'>"+ moment.tz(date, "America/New_York").fromNow() +"</td></tr>";
			if(count === 10) break;
		};
		document.getElementById('gh-body').innerHTML = row;
	});
</script>