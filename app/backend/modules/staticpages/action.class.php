<?php

class StaticPagesAction extends fvAction {
    
    function __construct () {
        parent::__construct(fvSite::$Layoult);
    }
    
    function executeIndex() {
        if (!fvRequest::getInstance()->isXmlHttpRequest()) {
            return self::$FV_OK;
        } else {
            return self::$FV_AJAX_CALL;
        }    
    }
        
    function executeEdit() {
        if (!fvRequest::getInstance()->isXmlHttpRequest()) {
            return self::$FV_OK;
        } else {
            return self::$FV_AJAX_CALL;
        }    
    }
    
    function executeSave() {
        $request = fvRequest::getInstance();
        
        if (!$StaticPage = StaticPagesManager::getInstance()->getByPk($request->getRequestParameter('id'))) {
            $StaticPage = new StaticPages();
        }
        $StaticPage->updateFromRequest($request->getRequestParameter('sp'));
        
        $isNew = $StaticPage->isNew();
        if ($save = $StaticPage->save()) {
            $this->setFlash("Данные успешно сохранены", self::$FLASH_SUCCESS);
        } else { 
            fvResponce::getInstance()->setHeader('X-JSON', json_encode($StaticPage->getValidationResult()));
            $this->setFlash("Ошибка при сохранении данных проверте правильность введенных данных", self::$FLASH_ERROR);
        }
        
        if($isNew && $save)
            fvResponce::getInstance()->setHeader('redirect', fvSite::$fvConfig->get('dir_web_root') . $request->getRequestParameter('module') . "/edit/?id=".$StaticPage->getPk() );
        elseif( $request->getRequestParameter('redirect') )
            fvResponce::getInstance()->setHeader('redirect', fvSite::$fvConfig->get('dir_web_root') . $request->getRequestParameter('module') );
        
        if (fvRequest::getInstance()->isXmlHttpRequest())
            return self::$FV_AJAX_CALL;
        else return self::$FV_OK;
    }
    function executeDelete() {
        $request = fvRequest::getInstance();
        $StaticPage = StaticPagesManager::getInstance()->getByPk($request->getRequestParameter('id'));
        fvSite::$DB->setOption("debug",1);
        
        if (!StaticPagesManager::getInstance()->isRootInstance($StaticPage)) {
            $this->setFlash("Запись не найдена", self::$FLASH_ERROR);
        } elseif($StaticPage->delete()) {            
            $this->setFlash("Данные успешно удалены", self::$FLASH_SUCCESS);                        
        } else {
            $this->setFlash("Ошибка при удалении", self::$FLASH_ERROR);
        }
               
        fvResponce::getInstance()->setHeader('redirect', fvSite::$fvConfig->get('dir_web_root') . $request->getRequestParameter('module') . "/");
        if (fvRequest::getInstance()->isXmlHttpRequest())
            return self::$FV_NO_LAYOULT;
        else return self::$FV_OK;
    }   
}

?>
