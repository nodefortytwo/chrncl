<?php
class UserLoginRoute extends Route{

	function validate(){
		if(isset($this->args[0])){
			$auth_method = User::getAuthMethod($this->args[0]);
		}else{
			$auth_method = User::getAuthMethod();
		}
		$auth_method->loginFormSubmit();


	}

}