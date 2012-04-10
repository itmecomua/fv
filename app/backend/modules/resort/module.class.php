<?php

class ResortModule extends fvModuleDictionary
{

    function __construct () 
    {
        $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
        parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"), 
                            fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"), 
                            fvSite::$Layoult,
                            ResortManager::getInstance());        
    }
    function showIndex()
    {
        $this->__assign("listCountry",CountryManager::getInstance()->htmlSelect('name','Выбрать..',null,"name asc"));
        $this->__assign("listWeight",ResortManager::getInstance()->getListWeight('Выбрать..'));
        
        return parent::showIndex();
    }
    
    function showEdit()
    {
        $this->__assign("listCountry",CountryManager::getInstance()->htmlSelect('name',null,null,"name asc"));
        $this->__assign("listWeight",ResortManager::getInstance()->getListWeight());
        return parent::showEdit();
    }

    
}

?>