<?php
class StoryViewRoute extends Route{

	public function validate(){
		if(empty($this->params[0])){
			throw new Exception('no sid dude');
		}
		$this->story = new Story($this->params[0]);
		if(!$this->story->exists){
			throw new Exception('story doesn\'t exist');
		}
		$this->story->loadEntities();
		return true;
	}

	public function render(){
		$page = new Template();
		$page->addTemplate('templates/story.view.html');
		$page->addVariable('story_title', $this->story['title']);
		$page->addVariable('story_content', Render::markDown($this->story['content']));
		$page->addVariable('author', $this->story['author.name']);
		$this->output = $page->render();
	}

}