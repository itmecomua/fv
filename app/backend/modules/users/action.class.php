<?php

class UsersAction extends fvAction {
    
    function __construct () {
        parent::__construct(fvSite::$Layoult);
    }

    function executeIndex() {
        if (fvRequest::getInstance()->isXmlHttpRequest())
            return self::$FV_NO_LAYOULT;
        else return self::$FV_OK;
    }
    
    function executeEdit() {
        if (fvRequest::getInstance()->isXmlHttpRequest())
            return self::$FV_AJAX_CALL;
        else return self::$FV_OK;
    }
    
    function executeSave() {
        $request = fvRequest::getInstance();
        
        if (!$User = UserManager::getInstance()->getByPk($request->getRequestParameter('id'))) {
            $User = new User();
        }
        
        $m = $request->getRequestParameter('m');
        
        if (!$User->isNew() && (strlen($m['password']) == 0)) {
            unset($m['password']);
            unset($m['password1']);
        }
        $User->updateFromRequest($m);
        if ($User->save()) {
            fvResponce::getInstance()->setHeader('Id', $User->getPk());
            $this->setFlash("Данные успешно сохранены", self::$FLASH_SUCCESS);
        } else { 
            fvResponce::getInstance()->setHeader('X-JSON', json_encode($User->getValidationResult()));
            $this->setFlash("Ошибка при сохранении данных проверте правильность введенных данных", self::$FLASH_ERROR);
        }
        if ($request->getRequestParameter('redirect')) {
            fvResponce::getInstance()->setHeader('redirect', fvSite::$fvConfig->get('dir_web_root') . $request->getRequestParameter('module') . "/");
        }        
        if (fvRequest::getInstance()->isXmlHttpRequest())
            return self::$FV_AJAX_CALL;
        else return self::$FV_OK;
    }    

    function executeDelete() {
        $request = fvRequest::getInstance();
        if (!$User = UserManager::getInstance()->getByPk($request->getRequestParameter('id'))) {
            $this->setFlash("Ошибка при удалении.", self::$FLASH_ERROR);
        } else {
            $User->delete();
            $this->setFlash("Данные успешно удалены", self::$FLASH_SUCCESS);
        }
        
        fvResponce::getInstance()->setHeader('redirect', fvSite::$fvConfig->get('dir_web_root') . $request->getRequestParameter('module') . "/");
        if (fvRequest::getInstance()->isXmlHttpRequest())
            return self::$FV_NO_LAYOULT;
        else return self::$FV_OK;
    }

    function executeGetparameterslist() {
        if (fvRequest::getInstance()->isXmlHttpRequest())
            return self::$FV_AJAX_CALL;
        else $this->redirect404();
    }
}

?>
