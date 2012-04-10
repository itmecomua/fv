<?php

require_once (fvSite::$fvConfig->get("path.entity") . 'staticpages.class.php') ;

class StaticPagesManager extends fvRootManager {
	
	protected function __construct () {
	    $objectClassName = substr(__CLASS__, 0, -7);
	    
	    // Tweak for ManagerManager Class ;)
	    if ($objectClassName == "") $objectClassName = "Manager";
	    
	    $this->rootObj = new $objectClassName();
	}
	
    static function getInstance()
    {
        static $instance; 
        
        $className = __CLASS__;
        
        if (!isset($instance)) {
            $instance = new self();
        }  
        return $instance;
    }
}
