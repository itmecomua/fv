<?php

class UserGroupsAction extends fvAction {
    
    function __construct () {
        parent::__construct(fvSite::$Layoult);
    }
    
    function executeIndex() {
        if (fvRequest::getInstance()->isXmlHttpRequest())
            return self::$FV_AJAX_CALL;
        else return self::$FV_OK;
    }
    
    
    function executeEdit() {
        if (fvRequest::getInstance()->isXmlHttpRequest())
            return self::$FV_AJAX_CALL;
        else return self::$FV_OK;
    }
    
    function executeSave() {
        $request = fvRequest::getInstance();
        
        if (!$UserGroup = UserGroupManager::getInstance()->getByPk($request->getRequestParameter('id'))) {
            $UserGroup = new UserGroup();
        }

        $mg = $request->getRequestParameter('mg');
        
        if (empty($mg['default_group']) && $UserGroup->default_group) {
            $this->setFlash("Ошибка при сохранении данных проверте правильность введенных данных", self::$FLASH_ERROR);
            if (fvRequest::getInstance()->isXmlHttpRequest())
                return self::$FV_AJAX_CALL;
            else return self::$FV_OK;
        }
        
        $UserGroup->updateFromRequest($mg);
        
        if ($UserGroup->save()) {
            if ($UserGroup->default_group)
                UserGroupManager::getInstance()->massUpdate("id <> " . $UserGroup->getPk(), array('default_group' => 0));
            
            $this->setFlash("Данные успешно сохранены", self::$FLASH_SUCCESS);
            fvResponce::getInstance()->setHeader('Id', $UserGroup->getPk()); 

            UserManager::getInstance()->massUpdate(sprintf('group_id = %d AND inherit = 1', $UserGroup->getPk()), array('permitions' => $UserGroup->permitions));
            
/*            $Users = UserManager::getInstance()->getAll('group_id = ? AND inherit = 1 AND global_rights = 1', null, null, $UserGroup->getPk());
            
            foreach ($Users as $User) {
                $User->permitions = $UserGroup->permitions;
                $User->save();
            }
*/        } else { 
            fvResponce::getInstance()->setHeader('X-JSON', json_encode($UserGroup->getValidationResult()));
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
        if (!$UserGroup = UserGroupManager::getInstance()->getByPk($request->getRequestParameter('id'))) {
            $this->setFlash("Ошибка при удалении.", self::$FLASH_ERROR);
        } else {
            $UserGroup->delete();
            $this->setFlash("Данные успешно удалены", self::$FLASH_SUCCESS);
        }
        
        fvResponce::getInstance()->setHeader('redirect', fvSite::$fvConfig->get('dir_web_root') . $request->getRequestParameter('module') . "/");
        if (fvRequest::getInstance()->isXmlHttpRequest())
            return self::$FV_NO_LAYOULT;
        else return self::$FV_OK;
    }
    
    function executeGetparams() {
        if (!fvRequest::getInstance()->isXmlHttpRequest()) return false;

        $Group = UserGroupManager::getInstance()->getByPk(fvRequest::getInstance()->getRequestParameter("group_id"));
        
        if (!($Group instanceof UserGroup)) return false;
        
        fvResponce::getInstance()->setHeader('X-JSON', json_encode($Group->permitions));
        return self::$FV_AJAX_CALL;
    }
}

?>
