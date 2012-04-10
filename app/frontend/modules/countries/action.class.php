<?php

class CountriesAction extends fvAction
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
     
     function executeGetHotels()
     {
        return $this->getRequest()->isXmlHttpRequest() ? self::$FV_AJAX_CALL : $this->redirect404();
     }    
     function executeRoute()
     {
         return $this->getRequest()->isXmlHttpRequest() ? self::$FV_AJAX_CALL : $this->redirect404();
     }         
     function executeTour() 
     {
         $page = fvRequest::getInstance()->getRequestParameter("page","int",0);
         return ($page > 0 ) ? self::$FV_AJAX_CALL : self::$FV_OK;
     }
     
 }
?>
