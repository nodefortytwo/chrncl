<?php
class StoryWriteRoute extends Route{
	
	public function render(){
		$page = new Template();
		Template::addJs('js/epiceditor.min.js');
		Template::addJs('js/story.write.js');

		$page->addTemplate('templates/story.write.html');
		$page->addVariable('sid', '');
		$page->addVariable('container_class', 'container');
		$page->addVariable('title_input', Form::elem(array(
												'type' => 'text',
												'label' => 'Title',
												'id' => 'story_title'
												)));
		$page->addVariable('location_input', Form::elem(array(
												'type' => '4sqlocation',
												'label' => 'Location',
												'id' => 'location'
												)));
		$page->addVariable('tag_input', Form::elem(array(
												'type' => 'tag',
												'label' => 'Story Tags',
												'id' => 'tags'
												)));
		$page->addVariable('submit', Form::elem(array(
												'type' => 'submit',
												'text' => 'Save Story',
												)));
		
		$this->output = $page->render();
	}

}