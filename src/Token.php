<?php
require_once($_SERVER["DOCUMENT_ROOT"] .'/sha/src/init.php');

/**
* Generates and validates token values to use in forms against CSRF attacks
*/
class Token {

	/**
	 * generates a new and unique token
	 * and returns it after setting it in the session
	 *
	 * @return string
	 */
	public static function generateToken($new=false){

		if(!Session::get('token') || $new){

			$token = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));

			Session::set('token', $token);
		}

		return Session::get('token');
	}

	/**
	 * validates a given token with the one stored in the session
	 *
	 * @return boolean
	 */
	public static function validateToken($token){

		$s_token = Session::get('token');
		if(!$s_token) return false; // if session doesn't have token

		return hash_equals($s_token, $token);
	}
}
?>