<?php

class SitesAction extends fvAction {
    
    function __construct () {
        parent::__construct(fvSite::$Layoult);
    }

    function executeIndex() {
        if (fvRequest::getInstance()->isXmlHttpRequest())
            return self::$FV_NO_LAYOULT;
        else return self::$FV_OK;
    }
    
    function executeSave() {
        $request = fvRequest::getInstance();
        
        if (!$Site = SiteManager::getInstance()->getByPk($request->getRequestParameter('id'))) {
            $Site = new Site();
        }
        $s = $request->getRequestParameter('s');
        
        $Site->updateFromRequest($s);

        if ($Site->save()) {
            fvResponce::getInstance()->setHeader('Id', $Site->getPk());
            $this->setFlash("Данные успешно сохранены", self::$FLASH_SUCCESS);
            fvResponce::getInstance()->setHeader('redirect', fvSite::$fvConfig->get('dir_web_root') . $request->getRequestParameter('module') . "/?id=". $Site->getPk());        
        } else { 
            fvResponce::getInstance()->setHeader('X-JSON', json_encode($Site->getValidationResult()));
            $this->setFlash("Ошибка при сохранении данных проверте правильность введенных данных", self::$FLASH_ERROR);
        }
        
        if (fvRequest::getInstance()->isXmlHttpRequest())
            return self::$FV_AJAX_CALL;
        else return self::$FV_OK;
    }
    
    function executeDelete() {
        $request = fvRequest::getInstance();
        if (!$Site = SiteManager::getInstance()->getByPk($request->getRequestParameter('id'))) {
            $this->setFlash("Ошибка при удалении.", self::$FLASH_ERROR);
        } else {
            $Site->delete();
            $this->setFlash("Данные успешно удалены", self::$FLASH_SUCCESS);
        }
        
        fvResponce::getInstance()->setHeader('redirect', fvSite::$fvConfig->get('dir_web_root') . $request->getRequestParameter('module') . "/");
        if (fvRequest::getInstance()->isXmlHttpRequest())
            return self::$FV_NO_LAYOULT;
        else return self::$FV_OK;        
    }

}

?>
