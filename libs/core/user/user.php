<?php
//hook_routes
function user_routes(){
	$routes = array();
	$routes['users'] = new UserRouteUsers();
	$routes['users/:user_id'] = new UserRouteUsers();
	$routes['user'] = new UserUserRoute();
	$routes['user/current'] = new UserCurrentRoute();
	$routes['user/login'] = new UserLoginRoute();
	$routes['user/register'] = new UserRegisterRoute();
	$routes['user/update/location'] = new UserUpdateLocationRoute();
	return $routes;
}

function user_init(){
}
