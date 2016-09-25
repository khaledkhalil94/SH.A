<?php 
require_once ($_SERVER["DOCUMENT_ROOT"]."/sha/src/init.php");


class View {
	
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

			$html = "<a href='". self::pLink($id) ."' title='{$date}'> ".get_timeago($date)."</a>";
		} elseif($type == 'p'){

			$post = Post::get_post($id, true);
			$date = $post['date'];

			$html = "<a href='". self::pLink($id) ."' title='{$date}'> ".get_timeago($date)."</a>";
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

}


?>