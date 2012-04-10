<?php

require_once (fvSite::$fvConfig->get("path.entity") . 'log.class.php') ;

class LogManager extends fvRootManager {
	
	protected function __construct () {
	    $objectClassName = substr(__CLASS__, 0, -7);
	    $this->rootObj = new $objectClassName();
	}
	
    static function getInstance()
    {
        static $instance; 
        
        $className = __CLASS__;
        
        if (!isset($instance)) {
            $instance = new $className();
        }  
        return $instance;
    }
}
