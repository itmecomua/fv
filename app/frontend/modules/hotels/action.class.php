<?php

class HotelsAction extends fvAction
{

	function __construct ()
	{
	    parent::__construct(fvSite::$Layoult);
	}

	 function executeIndex()
     {
        return $this->getRequest()->isXmlHttpRequest() ? self::$FV_AJAX_CALL : self::$FV_OK;
     }
     
     function executeList()
     {
        return $this->getRequest()->isXmlHttpRequest() ? self::$FV_AJAX_CALL : self::$FV_OK;
     }    
     
     function executeView()
     {
        return $this->getRequest()->isXmlHttpRequest() ? self::$FV_AJAX_CALL : self::$FV_OK;
     }
     
 }
