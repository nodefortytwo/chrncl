<?php

class TwitterConnectRoute extends Route{
	function render(){
		 /* Build TwitterOAuth object with client credentials. */

		$config = Config::get('twitter');
	    $connection = new TwitterOAuth($config['key'], $config['secret']);

	    /* Get temporary credentials. */
	    $request_token = $connection->getRequestToken(Config::get('protocol', 'http') . '://' . $_SERVER['HTTP_HOST'] . '' . $config['callback']);

	    /* Save temporary credentials to session. */
	    session(1)->oauth_token = $token = $request_token['oauth_token'];
	    session(1)->oauth_token_secret = $request_token['oauth_token_secret'];

	    $url = $connection->getAuthorizeURL($token);
	    $header = 'Location: ' . $url;
	    header($header);
	    die();
}
}