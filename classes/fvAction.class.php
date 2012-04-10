<?php

abstract class fvAction {
    
    public static $FV_ERROR = -1;
    
    public static $FV_OK = 1;
    public static $FV_NO_LAYOULT = 2;
    public static $FV_NO_ACTION = 3;
    public static $FV_NO_LAYOULT_MODULE = 4;
    public static $FV_AJAX_CALL = 5;
    
    public static $FLASH_SUCCESS = "success";
    public static $FLASH_ERROR = "error";
    public static $FLASH_INFO = "info";
    
/*    protected $template_dir;
    protected $compile_dir;/*/
    protected $current_page;
//    protected $className;
    
    public $returnValue = null;
    
    protected $instance;
    
    function __construct($current_page, fvRootManager $instance = null) 
    {
        $this->current_page = $current_page;
        $this->instance = $instance;
    }
    
    function getPage() {
        return $this->current_page;
    }
    
    function callAction($action) {   
        if (strlen((string)$action) == 0) $action = "index";
        $actionName = "execute" . ucfirst(strtolower($action));
        if (is_callable(array($this, $actionName))) {
            $res = call_user_func(array($this, $actionName));
            if (is_null($res)) $res = fvAction::$FV_OK;
            return $res;
        }
        else return fvAction::$FV_NO_ACTION;
    }
    
    function forward($url) {
        if (fvRequest::getInstance()->isXmlHttpRequest()) {
            fvResponce::getInstance()->setHeader('redirect', $url);
        } else {
            fvDispatcher::getInstance()->forward($url);
        }
    }
    
    function redirect($url, $application = FV_APP) {
        if (substr($url, 0, 1) == "@" || substr($url, 0, 4) != "http") {
            $url = fvSite::$fvConfig->get("path.application." . $application . ".web_root") . substr($url, 1);
        }
        if (fvRequest::getInstance()->isXmlHttpRequest()) {
            fvResponce::getInstance()->setHeader('redirectDirect', $url);
        } else {
            fvDispatcher::getInstance()->redirect($url);
        }
    }
    
    function redirect404() {
        fvDispatcher::getInstance()->redirect(fvSite::$fvConfig->get('page_404'), 0, 404);
    }
    
    function getRequest() {
        return fvSite::$fvRequest;
    }
    
    public function setFlash($message, $type = false) {
        $type = $type ? $type : self::$FLASH_INFO;
        fvResponce::getInstance()->setFlash($message, $type);
    }
    
    protected function __display($template_name) {
        $old_template_dir = fvSite::$Template->template_dir;
        $old_compile_dir = fvSite::$Template->compile_dir;
        
        fvSite::$Template->template_dir = $this->template_dir;
        fvSite::$Template->compile_dir = $this->compile_dir;
        
        $result = fvSite::$Template->fetch($template_name);
        
        fvSite::$Template->template_dir = $old_template_dir;
        fvSite::$Template->compile_dir = $old_compile_dir;
        
        return $result;
    }
    
    protected function __assign($key, $value = null) {
        if (is_null($value)) {
            fvSite::$Template->assign($key);
        }
        else {
            fvSite::$Template->assign($key, $value);
        }
    }
    
    protected function setHeader($key, $value)
    {
        fvResponce::getInstance()->setHeader($key, $value);
    }
    
    protected function getRequestParameter($name = "id", $type = "int", $default = 0)
    {
        return $this->getRequest()->getRequestParameter($name, $type, $default);
    }
    
    protected function executeIndex() 
    {
         return $this->getRequest()->isXmlHttpRequest() ? self::$FV_AJAX_CALL : self::$FV_OK;
    }
    
    protected function executeEdit() 
    {
         return $this->getRequest()->isXmlHttpRequest() ? self::$FV_AJAX_CALL : self::$FV_OK;
    }
    
    protected function executeSave()
    {
        if( is_null( $this->instance ))
            return fvDebug::debugs("Instance was Empty".__METHOD__);
        $m = $this->getRequestParameter('m', 'array', array() );
        $redirect = $this->getRequestParameter('redirect');
        $id = $this->getRequestParameter();
        try {
            $ex = $this->instance->getByPk( $id, true );
            $ex->updateFromRequest($m);
            if(  !$ex->isValid() )
                throw new EUserMessageError("Ошибка при сохранении", $ex);                      
            if(  !$ex->save() )
                throw new EUserMessageError("Ошибка при сохранении", $ex);                       
            $this->setFlash('Данные успешно сохранены', self::$FLASH_SUCCESS);
            $this->setHeader('redirect', fvSite::$fvConfig->get('dir_web_root') . $this->getRequest()->getRequestParameter('module') . ($redirect ? "" : "/edit/?id=" . $ex->getPk()) );
        } catch (EUserMessageError $e) {
            $this->setFlash($e->getMessage(), self::$FLASH_ERROR);
            $this->setHeader( 'X-JSON', json_encode($e->getValidationResult()) );
        } catch (EDatabaseError $db) {
            $this->setFlash($db->getMessage(), self::$FLASH_ERROR);
        }
        return $this->getRequest()->isXmlHttpRequest() ? self::$FV_AJAX_CALL : self::$FV_OK;
    }
    
    protected function executeDelete() 
    {
        if( is_null( $this->instance ))
            return fvDebug::debugs("Instance was Empty".__METHOD__);
        $id = $this->getRequestParameter();
        try {
              $ex = $this->instance->getByPk( $id );
              if( !$this->instance->isRootInstance( $ex ))
                throw new EUserMessageError("Запись не найдена");
              if( !$ex->delete() )  
                throw new EUserMessageError("Ошибка при удалении");
              $this->setFlash("Данные успешно удалены", self::$FLASH_SUCCESS);
        } catch (EUserMessageError $exc) {
            $this->setFlash($exc->getMessage(), self::$FLASH_ERROR);
        } catch (EDatabaseError $db) {
            $this->setFlash($db->getMessage(), self::$FLASH_ERROR);
        }
        $this->setHeader('redirect', fvSite::$fvConfig->get('dir_web_root') . $this->getRequest()->getRequestParameter('module') );
        return $this->getRequest()->isXmlHttpRequest() ? self::$FV_AJAX_CALL : self::$FV_OK;        
    }  
}

?>
