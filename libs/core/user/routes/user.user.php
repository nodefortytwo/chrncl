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
		var_dump($this->user);
	}

}