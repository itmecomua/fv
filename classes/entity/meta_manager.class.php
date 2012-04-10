<?php

require_once (fvSite::$fvConfig->get("path.entity") . 'meta.class.php') ;

class MetaManager extends fvRootManager {
	
    const NEWS_HEADING = "NEWS_HEADING";
    protected $_listTag = array(
        self::NEWS_HEADING => "Залоголовок новости"
    );
	protected function __construct () {
	    $objectClassName = substr(__CLASS__, 0, -7);
	    
	    $this->rootObj = new $objectClassName();
	}
	
    static function getInstance()
    {
        static $instance; 
        if (!isset($instance)) {
            $instance = new self();
        }  
        return $instance;
    }
    public function getListTag()
    {
        return $this->_listTag;
    }
}
