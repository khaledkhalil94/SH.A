<?php
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/src/init.php");


class View {

	public static function getPP($uid){
		global $connection;

		$sql = "SELECT path AS pic FROM ". TABLE_PROFILE_PICS ." WHERE user_id = :uid";

		$stmt = $connection->prepare($sql);

		$stmt->execute([':uid' => $uid]);

		$pic = $stmt->fetch()['pic'];

		return $pic ? $pic : DEF_PIC;
	}

	public static function qsn($id){
		return BASE_URL.'questions/question.php?id='.$id;
	}

	public static function user($id, $title=false, $class=false){

		$user = new User($id);
		$user = $user->user;

		if(!is_object($user)) return '';

		$classes = '';

		if($title) $classes .= 'user-title ';
		if($class) $classes .= "{$class} ";

		$html = "<a";

		if(!empty($classes)) {
			$classes = trim($classes);
			$html .= " class='{$classes}'";
		}

		if($title) $html .= " user-id='{$user->id}'";

		$html .= " href='". BASE_URL."user/{$user->id}/'>{$user->full_name}</a>";

		return $html;
	}

	public static function postDate($id){

		$type = Post::PorQ($id);

		if(($type == 'q') || $type == 'c'){

			$post = QNA::get_question($id) ?: (object) Comment::getComment($id);
			$date = $post->created;

			$html = "<a href='". self::pLink($id) ."' title='{$date}' class='datetime'>{$date}</a>";
		} elseif($type == 'p'){

			$post = Post::get_post($id, true);
			$date = $post['date'];

			$html = "<a href='". self::pLink($id) ."' title='{$date}' class='datetime'>{$date}</a>";
		} else {

			return false;
		}

		return $html;
	}

	public static function pLink($id){

		$type = Post::PorQ($id);

		if(($type == 'q') || $type == 'c'){

			$html = BASE_URL.'questions/question.php?id='.$id;

		} elseif($type == 'p'){

			$html = BASE_URL."user/posts/{$id}/";

		} else {

			return false;
		}

		return $html;
	}

	public static function userCard($uid){
		global $session;

		$userq = new User($uid);
		$user = $userq->get_user_info();

		$logged = $session->is_logged_in();

		if(!is_object($user)) die("User was not found.");

		if($logged){
			$is_frnd = $userq->is_friend($uid, USER_ID);
		}

		ob_start(); ?>
			<div class='ui card'>
			<a class='ui image' href='<?= BASE_URL."user/$user->id"; ?>/'>
				<img src='<?=$user->img_path?>'>
			</a>
			<div class='content'>
				<h3 class='header'><?= SELF::user($user->id); ?>
				<?php if($logged && $is_frnd) {?>
					<i title='You and <?=$user->firstName?> are friends' class='mdi mdi-account-multiple' style='color: #1ed02d; margin-left:5px;'></i>
				<?php } ?>
				</h3>
				<div class='meta'>
					<span class='username'>@<?= $user->username ?></span>
					<div class='user-points'>
						<a class='ui label' style='color:#04c704;' title='Total Points'>
						<i class='thumbs outline up icon'></i>
						<?= User::get_user_points($uid)?>
						</a>
					</div>
				</div>
			</div>
			<?php if(!$logged){ ?>
				<a href='/sha/login.php' class='ui button green'>Follow</a>
			<?php } elseif($uid === USER_ID){
			} elseif(User::is_flw($uid, USER_ID) !== true){?>
			<button id='user_flw' user-id='<?= $uid ?>' class='ui button green'>Follow</button>
			<?php } else { ?>
				<button id='user_unflw' user-id='<?= $uid ?>' class='ui button red'>Following</button>
			<?php } ?>
		</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	public static function getFeedPost($id){

		$post = new Post();
		$post = $post->get_post($id);

		ob_start(); ?>
			<div class="ui segment activity-view">
				<div class="header user-details">
					<div class="ui image mini">
						<a href="/sha/user/<?= $post->r_id ?>/"><img src="<?= $post->img_path ?>"></a>
					</div>
					<div class="summary">
						<p>You posted </p>&nbsp;
						<div class="time"><a href="/sha/user/posts/<?= $post->id ?>/"> A few seconds ago</a></div>
					</div>
				</div>
				<div class="content">
					<div class="text">
						<p><?= $post->content ?></p>
					</div>
					<div class="meta post-footer">
						<div class="post-points">
							<a class="like">
								<i class="like red icon"></i>0 Like
							</a>
						</div>
						<div class="post-comments">
							<a href="/sha/user/posts/<?= $post->id ?>/" class="comments">
								<i class="comments blue icon"></i>0 Comments
							</a>
						</div>
					</div>
				</div>
			</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	public static function renderComment($id){

		$comment = (object)Comment::getComment($id);

		ob_start(); ?>
		<div class="ui minimal comments">
			<div class="ui comment padded segment" id="<?= $comment->id; ?>" comment-id="<?= $comment->id; ?>">
				<div class="content">
					<div class="ui grid">
						<div class="two wide column cmt_avatar">
							<a href="/sha/user/<?= $comment->uid; ?>/">
								<img class="" src="<?= $comment->img_path; ?>">
							</a>
						</div>
						<div class="fourteen wide column user-details">
							<?= Self::user($comment->uid, true, 'author'); ?>
							<div class="metadata">
								<a class="time" href="question.php?id=<?= $comment->id; ?>">
									<span id="commentDate">A few seconds ago</span>
								</a>
							</div>
							<div class="text">
								<h4><?= $comment->content; ?></h4>
							</div>
								<div class="comment-points">
									<a class="comment-vote-btn">
										<i class="thumbs up circular icon"></i>
									</a>
									<span class="comment-votes-count"></span>
								</div>
						</div>
					</div>
					<div title="Actions" class="ui pointing dropdown" id="comment-actions">
						<i class="ellipsis link big horizontal icon"></i>
						<div class="menu">
							<div class="item" id="edit">
								<a class="edit">Edit</a>
							</div>
							<div class="item" id="del">
								<a class="delete">Delete</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php
		$comment = ob_get_contents();
		ob_end_clean();
		return $comment;
	}
}

?>
