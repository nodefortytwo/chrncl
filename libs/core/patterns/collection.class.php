<?php
class Collection implements Iterator{
    protected $collection, $default_cols = array('ID' => '_id'), $class_name = null, $position = 0;
    private $cursor;
    public $search = array();

    function __construct($search = null, $limit = null, $sort = null, $fragment = null) {
        if(!$this->collection || !$this->class_name){
            throw new Exception('collection and class_name must be specified');
        }
        
        $this->search = $search;
        $this->limit = $limit;
        $this->sort = $sort;
        $this->fragment = $fragment;

        $this->find();
    }
    
    function __get($var) {
        $mname = 'get' . ucfirst($var);
        if (method_exists($this, $mname)) {
            return call_user_func_array(array(
                $this,
                $mname
            ), array());
        }
    }
    
    function find() {
        //don't run a null search.
        if (!is_array($this->search)) {
            return;
        }
        if($this->fragment){
            $this->cursor = mdb()->{$this->collection}->find($this->search, $this->fragment);
        }else{
            $this->cursor = mdb()->{$this->collection}->find($this->search);
        }

        if($this->sort){
            $this->cursor->sort($this->sort);
        }
        if($this->limit){
            $this->cursor->limit($this->limit);
        }

        $this->cnt = $this->cursor->count(true);

    }
    
    function render($style = 'table', $args = array()) {
        $mname = 'render'.ucfirst($style);
        if(method_exists($this, $mname)){
            return call_user_func(array($this, $mname), $args);
        }else{
            throw new Exception('Style ' . $style . ' has not been implemented');
        }   
    }

    function renderTable($args){
        list($headers, $rows) = $this->getTableData($args);
        foreach($rows as $key=>$row){
            foreach($row as $ckey=>$col){
                if(is_object($col)){
                   switch(get_class($col)){
                       case 'MongoDate':
                           $row[$ckey] = Render::Date($col);
                           break;
                       default:
                           $col = (string) $col;
                   }
                    
                }
            }
            $rows[$key] = $row;
        }
        return Render::Table($headers, $rows, '');
    }
    
    function getTableData($args = array()){
        if (!isset($args['cols'])) {
            $args['cols'] = $this->default_cols;
        }

        $headers = array_keys($args['cols']);
        $rows = array();
        foreach ($this as $user) {
            $rows[] = $user->toArray($args['cols']);
        }
        
        if (isset($args['sort'])) {
            $index = array_search($args['sort'], array_values($args['cols']));
            $col = array();
            foreach ($rows as $row) {
                $col[] = $row[$index];
            }
            array_multisort($col, SORT_DESC, $rows);
        }

        return array($headers, $rows);
    }

    function fromObjects($objects) {
        //this is an experiment, a standard array should behave in almost the same way a mongo cursor so this should work.
        $this->cursor = new PseudoCursor($objects);
        $this->cnt = $this->cursor->count();
    }
    
    function getIds(){
        $this->ids = array();    
        foreach($this as $res){
            $this->ids[] = $res['_id'];
        }
        return $this->ids;
    }
    
    function getCnt(){
        $this->cnt = $this->cursor->count();
    }

    //iterator stuff
    function rewind() {
        $this->position = 0;
        $this->cursor->reset();
        $this->next();
    }

    function current() {
        if(is_object($this->cursor->current())){
            return $this->cursor->current();
        }
        $classname = $this->class_name;
        return new $classname($this->cursor->current());
    }

    function key() {
        return $this->cursor->key();
    }

    function next() {
       $this->cursor->next();
       $this->position++;
    }

    function valid() {
        return $this->cursor->valid();
    }
}

//emulate mogo cursor behaviour on a standard array, associated arrays should work too but not tested.
class PseudoCursor{
    private $data, $position = -1, $keys = array();
    function __construct($data){
        $this->data = $data;
        $this->keys = array_keys($this->data);
    }

    function rewind() {
        $this->position = -1;
        //reset($this->data);
    }

    function reset() {
        $this->rewind();
    }

    function current() {
        return $this->data[$this->key()];
    }

    function key() {
        if(isset($this->keys[$this->position])){
            return $this->keys[$this->position];
        }else{
            return null;
        }
    }

    function next() {
        $this->position++;
    }

    function valid() {
        if(!is_null($this->key())){
            return isset($this->data[$this->key()]);
        }else{
            return false;
        }
    }

    function count(){
        return count($this->data);
    }

}

