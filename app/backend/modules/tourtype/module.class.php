<?php

class TourTypeModule extends fvModuleDictionary
{

    function __construct () 
    {
        $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
        parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"), 
                            fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"), 
                            fvSite::$Layoult,
                            TourTypeManager::getInstance());        
    }
    
    function showEdit()
    {        
        $this->__assign("listWeight",HotelTypeManager::getInstance()->getListWeight());                        
        return parent::showEdit();
    }
    
}