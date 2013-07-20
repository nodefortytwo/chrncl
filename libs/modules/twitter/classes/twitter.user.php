<?php
class TwitterUser extends MongoBase{
	public $collection = 'twitterUser';

	function loadFromObject($obj){
		foreach($obj as $key=>$val){
			$this[$key] = $val;
		}

		$this['_id'] = $obj->id_str;
		$this->save();
		$this->loadFromId();
	}
}