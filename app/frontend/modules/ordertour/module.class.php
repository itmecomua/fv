<?php


class OrderTourModule extends fvModule 
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
        $url = fvRequest::getInstance()->getRequestParameter("url","string","");
        $tour = TourManager::getInstance()->getOneByurl($url);
            
        if(!TourManager::getInstance()->isRootInstance($tour)) return "Указанный тур не найден ....";
        $this->__assign("tour",$tour);   
        $this->__assign("captcha",fvCaptcha::getInstance()->generate());
        return $this->__display("index.tpl");
    }
    
}