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
    
    function showZone1()
    {
        $InsertedCode = CodeDictionaryManager::getInstance();
        $this->__assign("codeManager", $InsertedCode  );      
        return $this->__display("zone1.tpl");        
    }
    
    function showZone2()
    {
        $InsertedCode = CodeDictionaryManager::getInstance();
        $this->__assign("codeManager", $InsertedCode);      
        return $this->__display("zone2.tpl");        
    }
    
    function showItTour()
    {
        $InsertedCode = CodeDictionaryManager::getInstance();
        $this->__assign("codeManager", $InsertedCode);      
        return $this->__display("ittour.tpl");        
    }
    
    function showItTourScript()
    {
        $InsertedCode = CodeDictionaryManager::getInstance();
        $this->__assign("codeManager", $InsertedCode);      
        return $this->__display("ittourscript.tpl");        
    }
    

}