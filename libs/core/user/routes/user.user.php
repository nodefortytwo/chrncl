<?php
class UserUserRoute extends Route{

	function validate(){
		$user = User::currentUser();
		if($user){
			$this->user = $user;
		}else{
			redirect('/');
		}
		return true;
	}

	function render(){
		$user_profile = new Partial('templates/user.profile.partial.html');
		$user_profile->addVariables(array(
				'name' => $this->user['name'],
				'email' => $this->user['email']
			));

		$page = new Template();
		$page->addTemplate('templates/user.html');
		$page->addVariable('user_profile', $user_profile->render());
		$page->addVariable('stories', story_add_btn() . story_user_get_stories()->render());


		$this->output = $page->render();
	}

}