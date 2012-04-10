<?php

class fvParams {
	
	protected $params;
	
	protected function __construct() {
		$this->params = array();
	}
	
    public static function getInstance() {
        static $instance;

        if (!isset($instance)) {
             $instance = new self;
        }
        
        return $instance;
    }
    
    public function setParameter($name, $value) {
    	$this->params[$name] = $value;
    }
    
    public function getParameter($name, $default = null) {
    	if (isset($this->params[$name])) 
    		return $this->params[$name];
    	
    	return $default;
    }

	public function isParameterSet($name) {
		return isset($this->params[$name]);
	}
}

?>
