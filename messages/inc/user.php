<div class="ui msg-content">
	<div class="ui image tiny msg-image">
		<img src="<?= $img_path ?>">
	</div>
	<div class="msg-main">
		<div class="msg-user_info">
				<h4>Sent by <a href="/sha/user/<?= $message->u_id ?>/"><?= $message->u_name; ?></a></h4>
			<div class="time" id="s_msg_date" title="<?= $date; ?>"><?= $date; ?></div>
			<?php if($arch){ ?>
			<i title="This message is archived" class="archive icon"></i>
			<?php } ?>
			<div title="Actions" class="ui pointing dropdown" id="msg-actions">
				<i class="setting link large icon"></i>
				<div class="menu">
					<?php if($arch){ ?>
					<div class="item" id="msg_unarch">
						<a>unArchive</a>
					</div>
					<div class="item" id="msg_delete">
						<a>Delete</a>
					</div>
					<?php } else { ?>

					<?php if(!$staff){ ?>
					<div class="item" id="msg_unread">
						<a>Mark as unread</a>
					</div>
					<?php } ?>

					<div class="item">
						<a href="./?pm=<?= $message->u_id; ?>">View conversation</a>
					</div>
					<div class="item" id="msg_arch">
						<a>Archive</a>
					</div>
					<?php if(!$staff){ ?>
					<div class="item" id="msg_block">
						<a>Block user</a>
					</div>
					<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>
		<hr>
		<div class="msg-body">
			<div class="content"><?= $message->subject; ?></div>
		</div>
	</div>
</div>
<hr>
	<h3>Reply</h3>
<div class="ui segment message reply">
	<form action="#" class="ui form" id="send_msg">
		<div class="field">
			<textarea id="msg_context" rows="4" placeholder="Say something to <?= $message->u_name; ?>!"></textarea>
		</div>
		<input id="msg_token" type="hidden" value="<?= Token::generateToken(); ?>">
		<input id="send_to" type="hidden" value="<?= $message->u_id ?>">
		<button class="ui button green" type="submit">Send</button>
	</form>
</div>