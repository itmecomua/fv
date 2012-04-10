<?php

require_once (fvSite::$fvConfig->get("path.entity") . 'usergroup.class.php') ;

class UserGroupManager extends fvRootManager {
	
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

    public function getControl() {
        $UserGroups = $this->getAll();
        
        $result = array();
        
        foreach ($UserGroups as $UserGroup) {
            $result[$UserGroup->getPk()] = $UserGroup->get('group_name');
        }
        
        return $result;
    }
}
