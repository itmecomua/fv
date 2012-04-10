<?php

class OrderTourAction extends fvActionDictionary
{
   
    function __construct () 
    {
        parent::__construct(fvSite::$Layoult,OrderTourManager::getInstance());        
    }        

}
