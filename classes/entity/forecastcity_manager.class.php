<?php

require_once (fvSite::$fvConfig->get("path.entity") . 'forecastcity.class.php') ;

class ForecastCityManager extends fvRootManager {
	
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

}
