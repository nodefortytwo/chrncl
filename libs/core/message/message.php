<?php

function message($text, $level = 'info'){
    if(System::$cli){
        echo $text , "\n";
        return;
    }
    //sesh(1) tells the session class to start a session if one doesn't already exist;
    $messages = session(1)->messages;
    if(!$messages){
        $messages = array();
    }
    
    $messages[] = array(
        'text' => $text,
        'level' => $level
    );
    
    session()->messages = $messages;
}