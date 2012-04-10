<?php

class CodeDictionaryAction extends fvActionDictionary
{
   
    function __construct () 
    {
        parent::__construct(fvSite::$Layoult,CodeDictionaryManager::getInstance());        
    }        
    
    function executeZone1() 
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
    function executeZone2() 
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
