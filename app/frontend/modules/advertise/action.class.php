<?php

class AdvertiseAction extends fvAction {
    
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
    function executeHeaderLeft() 
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
    function executeHeaderRight() 
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
    function executeCrb() 
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

?>
