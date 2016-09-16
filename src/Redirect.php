<?php 
require_once( $_SERVER["DOCUMENT_ROOT"] .'/sha/src/init.php');

/**
 * Handles redirections 
 *
 */
class Redirect {


	/**
	 * redirects a user to a given url
	 * 
	 * @param @location string
	 *
	 * @return void
	 */
	public static function redirectTo($location = NULL){

		switch ($location) {
			case NULL:
				$location = BASE_URL;
				break;

			// redirect to 404.php page
			case '404':
				$location = BASE_URL."404.php";
				break;

			// redirect to the previous page
			case 'prev':
				$location = $_SERVER['HTTP_REFERER'];
				break;

			// redirect to the current page
			case 'self':
				$location = $_SERVER['PHP_SELF'];
				break;
			
			default:
				$location = $location;
				break;
		}
		if(!headers_sent()){

			header('Location: ' . $location);
			exit;

		} else { // if header is sent, output a script to redirect

			$output = '';

			$output .= "<script type=\"text/javascript\">\n";
			$output .= "window.location.href=\"$location\";\n";
			$output .= "</script>\n";

			echo $output;
			exit;
		}
	}

}
?>