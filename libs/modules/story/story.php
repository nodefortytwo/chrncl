 <?php

 function story_routes(){
 	$routes = array();

 	$routes['story/write'] = new StoryWriteRoute();
 	$routes['story/save'] = new StorySaveRoute();
 	$routes['story'] = new StoryViewRoute();

 	return $routes;
 }

 function story_add_btn(){
 	return Render::link('Add Story', '/story/write', 'btn btn-warning');
 }

 function story_user_get_stories($uid = null){
 	if(!is_null($uid)){
 		$user = new User($uid);
 	}else{
 		$user = User::currentUser();
 	}

 	$search = array('author' => $user['_id']);
 	return new StoryCollection($search, null, array('created_at' => -1));
 }