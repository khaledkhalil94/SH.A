<form class="ui main index form" action="#" method="POST" id="feed-box">
	<div class="field">
		<textarea name="content" id="feed-submit-textarea" rows="2" style="height:auto;" placeholder="What's going on ?"></textarea>
	</div>
	<input type="hidden" name="feed-token" value="<?= Token::generateToken(); ?>" >
	<button name="submit" class="ui blue submit icon button">Post</button>
</form>
<script src="/scripts/feed.js"></script>