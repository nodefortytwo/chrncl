<?php

class TwitterCallbackRoute extends Route{
	function render(){
		/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
		$config = Config::get('twitter');
	    $connection = new TwitterOAuth($config['key'], $config['secret'], session()->oauth_token,  session()->oauth_token_secret);
		/* Request access tokens from twitter */
		$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
		$at = new TwitterAccessToken($access_token);
		$at->save();
		/* Save the access tokens. Normally these would be saved in a database for future use. */
		session(1)->access_token = $access_token;
		unset(session(1)->oauth_token);
		unset(session(1)->oauth_token_secret);
		session()->persist();
		message('Sucessfully connected to twitter');
		redirect(get_url('/'));
	}
}