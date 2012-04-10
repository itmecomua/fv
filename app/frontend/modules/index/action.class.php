<?php

class IndexAction extends fvAction {
    
    function __construct () 
    {
        parent::__construct(fvSite::$Layoult);
    }
    
    function executeIndex() 
    {
        if (!fvRequest::getInstance()->isXmlHttpRequest()) 
        {
            return self::$FV_OK;
        }
        else 
        {
            return self::$FV_AJAX_CALL;
        }   
    }
    
    function executeSwitcher()
    {
        return $this->getRequest()->isXmlHttpRequest() ? self::$FV_NO_LAYOULT : self::$FV_OK;
    }
    
}

?>
