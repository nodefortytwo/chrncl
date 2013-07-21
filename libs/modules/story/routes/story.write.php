<?php
class StoryWriteRoute extends Route{
	public function validate(){
		if(!empty($this->params[0])){
			$this->story = new Story($this->params[0]);
			if(!$this->story->exists){
				throw new Exception('Trying to edit a story that does not exist');
			}else{
				$this->sid = $this->params[0];
			}
		}else{
			$this->story = new Story();
			$this->sid = '';
		}
		if(!$this->user = User::currentUser()){
			throw new Exception('User not logged in');
		}
		return true;
	}

	public function render(){
		$page = new Template();
		Template::addJs('js/epiceditor.min.js');
		Template::addJs('js/story.write.js');

		$page->addTemplate('templates/story.write.html');
		$page->addVariable('sid', $this->sid);
		$page->addVariable('container_class', 'container');
		$page->addVariable('story_content', $this->story['content']);
		$page->addVariable('title_input', Form::elem(array(
												'type' => 'text',
												'label' => 'Title',
												'id' => 'story_title',
												'default' => $this->story['title']
												)));
		$page->addVariable('location_input', Form::elem(array(
												'type' => '4sqlocation',
												'label' => 'Location',
												'id' => 'location',
												'default' => $this->story['location.id']
												)));
		$page->addVariable('tag_input', Form::elem(array(
												'type' => 'tag',
												'label' => 'Story Tags',
												'id' => 'tags',
												'default' => $this->story['tags']
												)));
		$page->addVariable('submit', Form::elem(array(
												'type' => 'submit',
												'text' => 'Save Story',
												)));
		
		$this->output = $page->render();
	}

}