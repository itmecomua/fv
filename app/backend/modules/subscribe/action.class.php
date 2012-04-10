<?php
class SubscribeAction extends fvActionDictionary
{
   
    function __construct () 
    {
        parent::__construct(fvSite::$Layoult,SubscribeManager::getInstance());        
    }        
    /**
    * Скачивание CSV файла
    * 
    */
    function executeDownloadCSV()
    {       
        return self::$FV_AJAX_CALL;                    
    }

}
