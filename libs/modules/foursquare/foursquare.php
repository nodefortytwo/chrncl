<?php

function foursquare_routes(){
	$routes = array();

	$routes['foursquare/location/search'] = new FoursquareLocationSearchRoute();

	return $routes;
}


function foursquare(){
	$config = Config::get('foursquare');
	$foursquare = new FoursquareAPI($config['client_id'],$config['client_secret']);
	return $foursquare;
}

