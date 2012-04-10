<?php

class IndexAction extends fvAction {
    
    function __construct () {
        parent::__construct(fvSite::$Layoult);
    }
    
    function executeIndex() {
        if (!fvRequest::getInstance()->isXmlHttpRequest()) {
            $this->current_page->setTitle('Index Page');
            return self::$FV_OK;
        } else {
            return self::$FV_AJAX_CALL;
        }   
    }
    function executeGenerateurl() 
    {
        if (!fvRequest::getInstance()->isXmlHttpRequest()) {    
            return self::$FV_OK;
        } else {
            return self::$FV_AJAX_CALL;
        }   
    }
}

?>
