<?php

class AdvertiseAction extends fvAction
{

    function __construct ()
    {
        parent::__construct(fvSite::$Layoult, AdvertiseManager::getInstance() );
    }
    
    protected function executeSave()
    {
        $redirect = $this->getRequestParameter('redirect');
        $id = $this->getRequestParameter();
        $m = $this->getRequestParameter('m', 'array', array());        
        try {
            $ex = AdvertiseManager::getInstance()->getByPk( $id , true);
            
            $ex->addField('oldImage', 'string', $ex->image);            
            $ex->updateFromRequest($m);            
            if(  !$ex->isValid() )
                throw new EUserMessageError("Проверьте правильность заполнения данных", $ex);                
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
}
?>
