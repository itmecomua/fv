<?php

class TourTypeAction extends fvActionDictionary
{
    function __construct () 
    {
        parent::__construct(fvSite::$Layoult,TourTypeManager::getInstance());  
    }        
}