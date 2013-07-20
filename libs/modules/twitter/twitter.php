<?php

function twitter_routes(){
	$routes = array();
	$routes['twitter/connect'] = new TwitterConnectRoute();
    $routes['twitter/callback'] = new TwitterCallbackRoute();
	return $routes;
}

function twitter($access_token = null){
	static $twitter_connection;
    if (!$twitter_connection) {
        if (!$access_token) {
            if (!session()->access_token || !isset(session()->access_token['oauth_token'])) {

                redirect(get_url('/twitter/connect/'));
            }

            $access_token =  new AccessToken();
        }
        $config = Config::get('twitter');
        $twitter_connection = new TwitterOAuth($config['key'], $config['secret'], $access_token['oauth_token'], $access_token['oauth_token_secret']);
    }
    return $twitter_connection;
}

function twitter_active_user(){

    $at = session()->access_token;
    if(!empty($at)){
        return $at['user_id'];
    }else{
        return false;
    }

}