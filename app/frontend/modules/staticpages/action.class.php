<?php

class StaticPagesAction extends fvAction {
    
	function __construct () {
	    parent::__construct(fvSite::$Layoult);
	}
    
    function executeIndex() {
        /*if (!fvRequest::getInstance()->isXmlHttpRequest()) {
            return self::$FV_OK;
        } else {
            return self::$FV_AJAX_CALL;
        }*/   
    }
        
}

?>
