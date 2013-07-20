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
		return true;
	}

	public function render(){
		if(!isset($this->story)){
			$this->story = new Story();
			$this->story['created'] = new MongoDate();
			$this->story['stats'] = array('likes' => 0, 'reads' => 0);
		}
		$this->story['title'] = $this->params['story_title'];
		$this->story['content'] = $this->params['story_content'];
		$this->story['location_id'] = $this->params['location'];
		$this->story['tags'] = explode(',', $this->params['tags']);
		$this->story['author'] = $this->user['_id'];
		$this->story['updated'] = new MongoDate();

		$this->story->save();

		message('Story Saved!');
		redirect('/user/');

	}

}
