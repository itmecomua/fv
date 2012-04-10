<?php


class OrderModule extends fvModule 
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
       $this->__assign("captcha",fvCaptcha::getInstance()->generate()); 
       return $this->__display("index.tpl");
    }
    
}