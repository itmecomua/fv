<?php

class MenuAction extends fvAction
{

	function __construct ()
	{
	    parent::__construct(fvSite::$Layoult);
	}

	 function executeIndex()
     {
        return $this->getRequest()->isXmlHttpRequest() ? self::$FV_AJAX_CALL : self::$FV_OK;
     }
     function executeMain()
     {
        return $this->getRequest()->isXmlHttpRequest() ? self::$FV_AJAX_CALL : self::$FV_OK;
     }
     function executeAdditional()
	 {
        return $this->getRequest()->isXmlHttpRequest() ? self::$FV_AJAX_CALL : self::$FV_OK;
     }
 }
?>
