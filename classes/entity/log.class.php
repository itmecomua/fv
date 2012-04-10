<?php

class Log extends fvRoot {
    
    const OPERATION_INSERT = 'insert';
    const OPERATION_DELETE = 'delete';
    const OPERATION_UPDATE = 'update';
    const OPERATION_ERROR  = 'error';
    
    protected $currentEntity = '';
	
	function __construct () {
	    $this->currentEntity = __CLASS__;
        parent::__construct(fvSite::$fvConfig->get("entities.{$this->currentEntity}.fields"), 
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.table_name"), 
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.primary_key", "id"));
	}
}

?>
