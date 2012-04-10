<?php

class CodeDictionaryModule extends fvModuleDictionary
{

    function __construct () 
    {
        $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
        parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"), 
                            fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"), 
                            fvSite::$Layoult,
                            CodeDictionaryManager::getInstance());        
    }
    
    function showEdit()
    {
        $this->__assign("listPositions", $this->instance->getPosition() );
        return parent::showEdit();
    }
    
}

?>