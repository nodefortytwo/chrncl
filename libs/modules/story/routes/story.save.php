<?php
class StorySaveRoute extends Route{
	
	public function validate(){
		if(!empty($this->params['sid'])){
			$this->story = new Story($this->params['sid']);
			if(!$this->story->exists){
				throw new Exception('Trying to edit a story that does not exist');
			}
		}
		if(!$this->user = User::currentUser()){
			throw new Exception('User not logged in');
		}
		if(!isset($this->params['tags'])){
			$this->params['tags'] = array();
		}else{
			$this->params['tags'] = explode(',', $this->params['tags']);
		}
		$this->location = new Venue($this->params['location']);
		return true;
	}

	public function render(){
		if(!isset($this->story)){
			$this->story = new Story();
			$this->story['created'] = new MongoDate();
			$this->story['stats'] = array('likes' => 0, 'reads' => 0);
		}

		foreach($this->params['tags'] as $tag){
			add_tag($tag);
		}

		$this->story['title'] = $this->params['story_title'];
		$this->story['content'] = $this->params['story_content'];
		$this->story['location.id'] = $this->location['_id'];
		$this->story['location.lat'] = $this->location['location.lat'];
		$this->story['location.lng'] = $this->location['location.lng'];
		$this->story['tags'] = $this->params['tags'];
		$this->story['author'] = $this->user['_id'];
		$this->story['updated'] = new MongoDate();

		$this->story->save();

		message('Story Saved!');
		redirect('/user/');

	}

}
