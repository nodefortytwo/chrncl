<?php
class Story extends MongoBase{
	protected $collection = 'story', $obj_id = true;
	protected $actions = array(
							'view' => array(
									'url' => '/story/~/',
									'label' => 'View Story',
									'class' => 'btn-info'
								),
							'edit' => array(
									'url' => '/story/edit/~/',
									'label' => 'Edit',
									'class' => 'btn-info'
								)
						);

	public function loadEntities(){

		$this['author'] = new User($this['author']);

	}

	public function save(){
		if(get_class($this['author']) != 'MongoId'){
			$this['author'] = $this['author']['_id'];
		}
		parent::save();
	}
}

class StoryCollection extends Collection{
	protected $collection = 'story', $class_name = 'Story';
	protected $default_cols = array(
									'ID' => '_id',
									'Title' => 'title',
									'Updated' => 'updated',
									'' => 'actions'
								);

}