<?php
class TwitterAccessToken extends MongoBase{
	public $collection = 'twitterAccessToken', $obj_id = true;

	function __construct($rec = null){

		if(is_null($rec)){
			$res = mdb()->{$this->collection}->findOne(array('oauth_token' => session()->access_token['oauth_token']));
			if(!is_null($res)){
				$rec = $res['_id'];
			}else{
				return false;
			}
		}

		parent::__construct($rec);
		if($this->exists){
			$this->verify();
		}
	}

	public function verify(){
		if(isset($this['validated'])){
            if((time() - $this['validated']->sec) < 3600 * 24){
                return true;
            }
        }
        $valid = twitter($this->data)->get('account/verify_credentials');
        if(isset($valid->errors)){
            foreach($valid->errors as $error){
                if($error->code == 89){
                    $this->delete();
                    return false;
                }
            }
        }
        $user = new TwitterUser($valid);
        $this['screen_name'] = $valid->screen_name;
        $this['id'] = $valid->id_str;
        $this['name'] = $valid->name;

        $this['validated'] = new MongoDate(time());
        $this->save();
        return true;
	}
}