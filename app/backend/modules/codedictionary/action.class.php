<?php

class CodeDictionaryAction extends fvActionDictionary
{
   
    function __construct () 
    {
        parent::__construct(fvSite::$Layoult,CodeDictionaryManager::getInstance());        
    }        
}
