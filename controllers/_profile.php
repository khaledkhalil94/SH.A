<?php 
require_once( $_SERVER["DOCUMENT_ROOT"] .'/sha/src/init.php');

if(!$session->is_logged_in()) Redirect::redirectTo('/sha');

//Allow access only via ajax requests
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' ) {

	Redirect::redirectTo('404');
}


switch ($_POST['action']) {

	// follow a user
	case 'follow':

		$userID = $_POST['id'];

		$follow = User::follow($userID);

		if($follow === true){

			die(json_encode(['status' => true]));
		} else {
			
			die(json_encode(['status' => false, 'err' => $follow]));
		}

		break;

	// unfollow a user
	case 'unfollow':

		$userID = $_POST['id'];

		$unfollow = User::unfollow($userID);

		if($unfollow === true){

			die(json_encode(['status' => true]));
		} else {
			
			die(json_encode(['status' => false]));
		}

		break;

	// get user profile card
	case 'profile_card':

		$uid = $_POST['id'];
		$user = User::get_user_info($uid);

		if(!is_object($user)) die("User was not found.");
		
		$html = 
			"<div class='ui card'>
			<div class='image'>
			<img class='ui image small' src='{$user->img_path}'>
			</div>
			<div class='content'>
			<h3 class='header'>{$user->full_name}</h3>
			<div class='meta'>
			<span class='date'><a href='".BASE_URL."user/{$user->id}'>@{$user->username}</a></span>
			</div>
			<div class='description'>
			<div class='user-points'>
			<a class='ui label' style='color:#04c704;' title='Total Points'>
			<i class='thumbs outline up icon'></i>
			". User::get_user_points($uid) ."
			</a>
			</div>
			</div>
			</div>
			<button class='ui button green'>Follow</button>
			<button class='ui button red'>unFollow</button>
			</div>";
		die($html);
		break;

	default:
		break;
}
