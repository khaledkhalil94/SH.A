<?php
require_once ("../src/init.php");
$id = sanitize_id($_GET['id']) ?: null;
if(!$id) $session->message("Invalid url.", "/sha/404.php", "warning");

$user = $student->get_user_info($id);

if(!$user){
	$session->message("Page was not found!", BASE_URL, 'danger');
}

$img_path = $user->img_path;
$name = $user->full_name;
$username = $user->username;
$id = $user->id;
$email = $user->email;
$location = $user->address;
$gender = $user->gender;
$phoneNumber = $user->phoneNumber;
$register_date = $user->joined;
$birthDate = $user->birth_date;
$about = $user->about;

$website = $user->website;
$skype = $user->skype;
$twitter = $user->twitter;
$github = $user->github;
$facebook = $user->facebook;

$emptyLinks = false;
if(empty($website)&&empty($skype)&&empty($twitter)&&empty($github)&&empty($facebook)) $emptyLinks = true;


$self = $session->userCheck($user);

$emailP = $user->email_privacy;
$locationP = $user->location_privacy;
$phoneNumberP = $user->phoneNumber_privacy;
$genderP = $user->gender_privacy;
$birthP = $user->birthDate_privacy;

$pub = $emailP && $locationP && $phoneNumberP && $genderP && $birthP ? true : false;
$custom =($emailP || $locationP || $phoneNumberP || $genderP || $birthP) && !$pub ? true : false;

$linksP = $user->links_privacy;

$public = "<i title=\"Public\" class=\"world icon\"></i>";
$private = "<i title=\"Private\" class=\"lock icon\"></i>";

$has_pic = Images::has_pic($user->id);

//$pic = Images::get_pic_info($id);

$pageTitle = $name;
$sec = 'profile';
include (ROOT_PATH . "inc/head.php");
 ?>
 <script>var userID = <?= $id ?>;</script>
<div class="container section">
<?php 

if($session->is_logged_in() && !$self){

	require('templates/user-profile-user.php');

} elseif($self) {

	require_once('templates/user-profile-self.php');

} else {

	require_once('templates/user-profile-pub.php');

}?>
</div>
<script src="/sha/scripts/user-src.js"></script>
<?php
include (ROOT_PATH . 'inc/footer.php');
?>
