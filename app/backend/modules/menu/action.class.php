<?php

class MenuAction extends fvAction {
    
    function __construct () {
        parent::__construct(fvSite::$Layoult);
    }
    
    function executeIndex()
    {
        return $this->getRequest()->isXmlHttpRequest() ? self::$FV_AJAX_CALL : self::$FV_OK;
    }
    
    function executeEdit()
    {
        return $this->getRequest()->isXmlHttpRequest() ? self::$FV_AJAX_CALL : self::$FV_OK;
    }
    
    function executeSave()
    {
        $id = $this->getRequestParameter();
        $m = $this->getRequestParameter('m', 'array', array());
        $redirect = $this->getRequestParameter('redirect');
        try {
            $ex = MenuManager::getInstance()->getByPk($id, true);
            if(!$m['parent_id'])
            {
                unset($m['parent_id']);
                if(!$ex->isNew())
                    $ex->setNULL('parent_id');
            }
            $ex->updateFromRequest($m);

            if( !$ex->isValid() )
                throw new EUserMessageError("Ошибка при сохраннении. Проверьте правильность данных", $ex);
            if( !$ex->save() )
                throw new EUserMessageError("Ошибка при сохраннении. Проверьте правильность данных", $ex);
            $this->setFlash("Данные успешно сохранены", self::$FLASH_SUCCESS);
            $this->setHeader('redirect', fvSite::$fvConfig->get('dir_web_root') . $this->getRequest()->getRequestParameter('module') . ($redirect ? "" :"/edit/?id=".$ex->getPk()));
        } catch (EUserMessageError $exc) {
            $this->setFlash($exc->getMessage(), self::$FLASH_ERROR);
            $this->setHeader('X-JSON', json_encode($exc->getValidationResult()));
        }
        
        return $this->getRequest()->isXmlHttpRequest() ? self::$FV_AJAX_CALL : self::$FV_OK;
    }
    
    function executeSaveWt()
    {
        $d = $this->getRequestParameter('d', 'array', array());
        $type_id = $this->getRequestParameter('type', 'int', 0);
        fvSite::$DB->autoCommit(false);
        try
        {
            $typeName = MenuManager::getInstance()->getTypeMenu($type_id);
            if( is_array($typeName) )
                throw new EUserMessageError("Тип меню не найден");
            foreach($d as $id => $wt) 
            {
                $menu = MenuManager::getInstance()->getByPk($id);
                if( !MenuManager::getInstance()->isRootInstance($menu))
                     throw new EUserMessageError("Пункт меню c id={$id} не найден. Сохранение не возвможно.");
                $menu->set("weight", $wt);
                if( !$menu->save() )
                    throw new EUserMessageError("Ошибка при сохранении");
            }
            fvSite::$DB->commit();
            $this->setFlash("Данные успешно сохранены", self::$FLASH_SUCCESS);
        } catch (EUserMessageError $e) {
            fvSite::$DB->rollback();
            $this->setFlash($e->getMessage(), self::$FLASH_ERROR);
        } catch (EDatabaseError $db ) {
            fvSite::$DB->rollback();
            $this->setFlash("Ошибка базы данных: " . $db->getMessage(), self::$FLASH_ERROR);
        }
        fvSite::$DB->autoCommit(true);
        return $this->getRequest()->isXmlHttpRequest() ? self::$FV_AJAX_CALL : self::$FV_OK;
    }
    
    function executeDelete() {
        $request = fvRequest::getInstance();
        if (!$ex = MenuManager::getInstance()->getByPk($request->getRequestParameter('id'))) {
            $this->setFlash("Ошибка при удалении.", self::$FLASH_ERROR);
        } else {
            $ex->delete();
            $this->setFlash("Данные успешно удалены", self::$FLASH_SUCCESS);
        }
        
        fvResponce::getInstance()->setHeader('redirect', fvSite::$fvConfig->get('dir_web_root') . $request->getRequestParameter('module') . "/");
        if (fvRequest::getInstance()->isXmlHttpRequest())
            return self::$FV_NO_LAYOULT;
        else return self::$FV_OK;
    }  
}

?>
