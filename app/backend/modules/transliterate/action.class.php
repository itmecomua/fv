<?php

class TransliterateAction extends fvAction
{
    public $moduleName;

	function __construct ()
	{
	    $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
        parent::__construct(fvSite::$Layoult);
	}

	function executeIndex()
	 {
	 	if (!fvRequest::getInstance()->isXmlHttpRequest())
        {
     		return self::$FV_OK;
        }
        else
        {
            return self::$FV_AJAX_CALL;
        }
    }
    
    function executeGenerateurl()
     {
         if (!fvRequest::getInstance()->isXmlHttpRequest())
        {
             return self::$FV_OK;
        }
        else
        {
            return self::$FV_AJAX_CALL;
        }
    }
    
    function executeSave()
    {
        $m = $this->getRequest()->getRequestParameter('m', 'array');
        $result = fvLang::getInstance()->saveConfig($m);
        if($result)
            $this->setFlash('Данные успешно сохранены', self::$FLASH_SUCCESS);
        else $this->setFlash('Ошибка при сохранении данных', self::$FLASH_ERROR);
        return $this->getRequest()->isXmlHttpRequest() ? self::$FV_AJAX_CALL : self::$FV_OK;
    }
    
    
    
  }
?>
