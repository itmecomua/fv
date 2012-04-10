<?php

class IconAction extends fvAction {
    
    function __construct () 
    {
        parent::__construct(fvSite::$Layoult);
    }
    
    function executeIndex() 
    {
        if (!fvRequest::getInstance()->isXmlHttpRequest()) 
        {
            return self::$FV_NO_LAYOULT;
        }
        else 
        {
            return self::$FV_AJAX_CALL;
        }   
    }
   
}
