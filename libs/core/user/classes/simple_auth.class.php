<?php
class UserSimpleAuth implements iAuthMethod{
	public function login(){
		list($email, $password) = func_get_args();
		$password = md5($password.count($email));

		$user = mdb()->user->findOne(array('simple.email' => $email, 'simple.password' => $password));
		if($user){
			session(1)->user_id = $user['_id'];
			session()->persist();
			redirect('/user/');
		}else{
			message('Login Failed');
			redirect('/');
		}
	}

	public function logout(){

	}

	public function currentUser(){
		if(isset(session()->user_id)){
 			return new User(session()->user_id);
 		}else{
 			return false;
 		}
	}

	public function registerform(){

		$form = new Form(array(
				'action' => '/user/register/~/simple/',
				'id' => 'registerForm',
				'method' => 'POST'
			));

		$form->e(array(
				'type' => 'text',
				'label' => 'Name',
				'id' => 'name'
			));
		$form->row();
		$form->e(array(
				'type' => 'text',
				'label' => 'e-mail',
				'id' => 'email'
			));
		$form->row();
		$form->e(array(
				'type' => 'password',
				'label' => 'Password',
				'id' => 'password'
			));
		$form->row();
		$form->e(array(
				'type' => 'password',
				'id' => 'password2',
				'placeholder' => 'repeat password'
			));
		$form->row();
		$form->e(array(
				'type' => 'submit',
				'text' => 'register',
				'class' => 'pull-right'
			));

		return $form->render();
	}
	public function registerFormSubmit(){

		if(is_null(get('email'))){redirect('/');}
		if(is_null(get('password'))){redirect('/');}
		if(get('password') != get('password2')){redirect('/');}

		$email = get('email');
		$password = md5(get('password').count($email));
		$user = mdb()->user->findOne(array('simple.email' => $email, 'simple.password' => $password));
		if($user){
			$this->login($email, $password);
		}else{
			if(mdb()->user->findOne(array('email' => $email))){redirect('/');}
			$user = new User();
			$user['name'] = get('name', 'Anon');
			$user['email'] = $email;
			$user['simple.email'] = $email;
			$user['simple.password'] = $password;
			$user->save();
			$this->login($email, $password);
		}
	}
	public function loginForm(){
		$form = new Form(array(
				'action' => '/user/login/~/simple/',
				'id' => 'loginForm',
				'method' => 'POST'
			));
		$form->row();
		$form->e(array(
				'type' => 'text',
				'label' => 'e-mail',
				'id' => 'email'
			));
		$form->row();
		$form->e(array(
				'type' => 'password',
				'label' => 'Password',
				'id' => 'password'
			));
		$form->row();
		$form->e(array(
				'type' => 'submit',
				'text' => 'login',
				'class' => 'pull-right'
			));
		return $form->render();
	}
	public function loginFormSubmit(){
		$this->login(get('email'), get('password'));
	}
}

User::registerAuthMethod(new UserSimpleAuth(), 'simple');