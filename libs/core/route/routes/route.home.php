<?php
class RouteRouteHome extends Route{

	function validate(){

		$user = User::currentUser();
		if($user){
			redirect('/user/');
		}

		return true;

	}

	function render(){
		$page = new Template();
		$page->addTemplate('templates/home.html');
		$page->addVariable('register_form', User::registerForm());
		$page->addVariable('login_form', User::loginForm());
		$this->output = $page->render();
	}
}