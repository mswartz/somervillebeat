<?php

/**
 * This file originally shipped as the suggested library from google:
 * https://developers.google.com/recaptcha/docs/php.  However, it fell short
 * for our use case because it used fsockopen with no fallback.
 * Therefore _recaptcha_http_post() has been rewritten to use the WP HTTP API.
 * It's also been pruned down to not include anything about the MailHide API
 * because those sections were not being used, and also because they used base64_encode(),
 * which is banned on some boutique wordpress hosts.
 *
 */

/**
 * The reCAPTCHA server URL's
 */
define("RECAPTCHA_API_SERVER", "http://www.google.com/recaptcha/api");
define("RECAPTCHA_API_SECURE_SERVER", "https://www.google.com/recaptcha/api");
define("RECAPTCHA_VERIFY_SERVER", "www.google.com");

/**
 * Encodes the given data into a query string format
 * @param $data - array of string elements to be encoded
 * @return string - encoded request
 */
function _recaptcha_qsencode ($data) {
	$req = "";
	foreach ( $data as $key => $value )
		$req .= $key . '=' . urlencode( stripslashes($value) ) . '&';

	// Cut the last '&'
	$req=substr($req,0,strlen($req)-1);
	return $req;
}



/**
 * Submits an HTTP POST to a reCAPTCHA server
 *
 * @param string $host
 * @param string $path
 * @param array $body
 * @param int port
 * @return array response
 * @author Scott Fennell
 */
function _recaptcha_http_post( $host, $path, $body, $port = 80 ) {

	// Build the url to which we'll send our request.
	$url = esc_url( $host.$path );

	// Encode the body as a url query string.
	$req = _recaptcha_qsencode( $body );

	// Get the content length.
	$length =  strlen( $req );

	// Build the request headers.
	$headers = array(
		'Content-Type'   => 'application/x-www-form-urlencoded',
		'Content-Length' => $length,
		'User-Agent'     => 'reCAPTCHA/PHP',
	);

	// Build an args array for wp_remote_post.
	$args = array(
		'method'      => 'POST',
		'timeout'     => 5,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking'    => true,
		'headers'     => $headers,
		'body'        => $body,
		'cookies'     => array()
	);

	// Send the request to reCaptcha.
	$response_array = wp_remote_post(
		$url,
		$args
	);

	// The response header from reCaptcha.
	$response_head_array = $response_array[ 'headers' ];

	// The response header as a string.
	$response_head_string = implode( "\r\n\r\n", $response_head_array );

	// The response body from reCaptcha.
	$response_body_string = $response_array[ 'body' ];

	// The response array formatted as expected elsewhere in this library.
	$response = array( $response_head_string, $response_body_string );

	return $response;

}



/**
 * Gets the challenge HTML (javascript and non-javascript version).
 * This is called from the browser, and the resulting reCAPTCHA HTML widget
 * is embedded within the HTML form it was called from.
 * @param string $pubkey A public key for reCAPTCHA
 * @param string $error The error given by reCAPTCHA (optional, default is null)
 * @param boolean $use_ssl Should the request be made over ssl? (optional, default is false)

 * @return string - The HTML to be embedded in the user's form.
 */
function recaptcha_get_html ($pubkey, $error = null, $use_ssl = false)
{
	if ($pubkey == null || $pubkey == '') {
		die ("To use reCAPTCHA you must get an API key from <a href='https://www.google.com/recaptcha/admin/create'>https://www.google.com/recaptcha/admin/create</a>");
	}

	if ($use_ssl) {
		$server = RECAPTCHA_API_SECURE_SERVER;
	} else {
		$server = RECAPTCHA_API_SERVER;
	}

	$errorpart = "";
	if ($error) {
		$errorpart = "&amp;error=" . $error;
	}
	return '<script type="text/javascript" src="'. $server . '/challenge?k=' . $pubkey . $errorpart . '"></script>

	<noscript>
  		<iframe src="'. $server . '/noscript?k=' . $pubkey . $errorpart . '" height="300" width="500" frameborder="0"></iframe><br/>
  		<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
  		<input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
	</noscript>';
}




/**
 * A ReCaptchaResponse is returned from recaptcha_check_answer()
 */
class ReCaptchaResponse {
	var $is_valid;
	var $error;
}


/**
 * Calls an HTTP POST function to verify if the user's guess was correct
 * @param string $privkey
 * @param string $remoteip
 * @param string $challenge
 * @param string $response
 * @param array $extra_params an array of extra variables to post to the server
 * @return ReCaptchaResponse
 */
function recaptcha_check_answer ($privkey, $remoteip, $challenge, $response, $extra_params = array())
{
	if ($privkey == null || $privkey == '') {
		die ("To use reCAPTCHA you must get an API key from <a href='https://www.google.com/recaptcha/admin/create'>https://www.google.com/recaptcha/admin/create</a>");
	}

	if ($remoteip == null || $remoteip == '') {
		die ("For security reasons, you must pass the remote ip to reCAPTCHA");
	}



	//discard spam submissions
	if ($challenge == null || strlen($challenge) == 0 || $response == null || strlen($response) == 0) {
		$recaptcha_response = new ReCaptchaResponse();
		$recaptcha_response->is_valid = false;
		$recaptcha_response->error = 'incorrect-captcha-sol';
		return $recaptcha_response;
	}

	$response = _recaptcha_http_post (RECAPTCHA_VERIFY_SERVER, "/recaptcha/api/verify",
		array (
			'privatekey' => $privkey,
			'remoteip' => $remoteip,
			'challenge' => $challenge,
			'response' => $response
		) + $extra_params
	);

	$answers = explode ("\n", $response [1]);
	$recaptcha_response = new ReCaptchaResponse();

	if (trim ($answers [0]) == 'true') {
		$recaptcha_response->is_valid = true;
	}
	else {
		$recaptcha_response->is_valid = false;
		$recaptcha_response->error = $answers [1];
	}
	return $recaptcha_response;

}

/**
 * gets a URL where the user can sign up for reCAPTCHA. If your application
 * has a configuration page where you enter a key, you should provide a link
 * using this function.
 * @param string $domain The domain where the page is hosted
 * @param string $appname The name of your application
 */
function recaptcha_get_signup_url ($domain = null, $appname = null) {
	return "https://www.google.com/recaptcha/admin/create?" .  _recaptcha_qsencode (array ('domains' => $domain, 'app' => $appname));
}

function _recaptcha_aes_pad($val) {
	$block_size = 16;
	$numpad = $block_size - (strlen ($val) % $block_size);
	return str_pad($val, strlen ($val) + $numpad, chr($numpad));
}