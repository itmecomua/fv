<?php

class OrderAction extends fvActionDictionary
{
   
    function __construct () 
    {
        parent::__construct(fvSite::$Layoult,OrderManager::getInstance());        
    }        

}
