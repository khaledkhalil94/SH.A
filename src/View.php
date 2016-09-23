<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/src/init.php");


class View {
	
	public static function qsn($id){
		return BASE_URL.'questions/question.php?id='.$id;
	}

	public static function user($id){

		$user = new User($id);
		$user = $user->user;

		if(!is_object($user)) return '';

		$html = "<a href='". BASE_URL."user/{$user->id}/'>{$user->full_name}</a>";
		return $html;
	}

	public static function postDate($id){

		$type = Post::PorQ($id);

		if($type == 'q'){

			$post = QNA::get_question($id);
			$date = $post->created;

			$html = "<a href='". BASE_URL ."questions/question.php?id={$id}'>".get_timeago($date)."</a>";
		} elseif($type == 'c'){

			$post = (object) Comment::getComment($id);
			$date = $post->created;

			$html = "<a href='". BASE_URL ."questions/question.php?id={$id}'>".get_timeago($date)."</a>";
		}

		return $html;
	}

}


?>