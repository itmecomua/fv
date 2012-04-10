<?php

class IndexModule extends fvModule 
{
    function __construct () 
    {
        $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
        parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"), 
        fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"), 
        fvSite::$Layoult);
    }

    function showIndex() 
    {  
       return $this->__display("index.tpl");
    }   
        
    function showSwitcher()
    {
        $Langs = fvSite::$fvConfig->get('languages');
        $curURL = $this->getRequest()->getRequestParameter('__url') ? "/".$this->getRequest()->getRequestParameter('__url') : "";
        $this->__assign("Langs", $Langs);
        $this->__assign("url", $curURL);
        return $this->__display("switcher.tpl");
    } 
}

?>
