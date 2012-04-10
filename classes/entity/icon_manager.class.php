<?php
require_once (fvSite::$fvConfig->get("path.entity") . 'icon.class.php') ;

class IconManager extends fvRootManager 
{
    protected function __construct () 
    {        
        $objectClassName = substr(__CLASS__, 0, -7);        
        $this->_objectClassName = $objectClassName;
        $this->_className = __CLASS__;
        $this->rootObj = new $objectClassName();        
    }
    
    static function getInstance()
    {
        static $instance; 
        if (!isset($instance))
            $instance = new self();
        return $instance;
    }
    
}
