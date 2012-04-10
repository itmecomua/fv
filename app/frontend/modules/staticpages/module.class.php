<?php

class StaticPagesModule extends fvModule {
	
    function __construct () 
    {
	     $this->moduleName = strtolower(substr(__CLASS__, 0, -6));         
         parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"), 
                             fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"), 
                             fvSite::$Layoult);
	}

	
	function showFull () 
    {
	    return "";
	}
	
	function showShort($params) 
    {
	    
	    $this->count = intval($params['count_short']);
	    $this->moduleID = $this->getParams()->getParameter("moduleID");
	    
	    return $this->__display("short_list.tpl");
	    
	}
	
	function showOne($params) 
    {
	   $pageName = $params['tech_name'] or fvRequest::getInstance()->getRequestParameter("tech_name");
	   $Page = StaticPagesManager::getInstance()->getByTechUrl($pageName);
	               
	   if (count($Page) == 1 && is_object($Page = $Page[0])) 
       {
	       $this->__assign("sp", $Page);
	   }
       else 
       {
	       $this->__assign("sp", new StaticPages());
	   }
	   
	   return $this->__display("one.tpl");
	}
}

?>
