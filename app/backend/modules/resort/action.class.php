<?php

class ResortAction extends fvActionDictionary
{
   
    function __construct () 
    {
        parent::__construct(fvSite::$Layoult,ResortManager::getInstance());        
    }        
}
