<?php

class HotelTypeAction extends fvActionDictionary
{
   
    function __construct () 
    {
        parent::__construct(fvSite::$Layoult,HotelTypeManager::getInstance());        
    }        
}
