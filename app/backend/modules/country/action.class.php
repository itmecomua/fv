<?php

class CountryAction extends fvActionDictionary
{
   
    function __construct () 
    {
        parent::__construct(fvSite::$Layoult,CountryManager::getInstance());        
    }        
        function executeEditPhoto()
    {
        return $this->getRequest()->isXmlHttpRequest() ? self::$FV_AJAX_CALL : self::$FV_OK;
    }
    /**
    * Сохранение фото
    * 
    */
    function executeSavePhoto()
    {
        $id = $this->getRequestParameter();        
        $photo = $this->getRequestParameter('photo', 'array', array() );        
        fvSite::$DB->autoCommit(false);
        try {
            $country = CountryManager::getInstance()->getByPk($id);
            if( !CountryManager::getInstance()->isRootInstance($country) )
                throw new EUserMessageError("Страна не найдена");                
            CountryMediaManager::getInstance()->saveMassPhoto($photo, $country);                        
            fvSite::$DB->commit();
            $this->setHeader('message', json_encode("Данные успешно сохранены"));            
        } catch (EUserMessageError $exc ) {
            fvSite::$DB->rollback();
            $this->setHeader('exception', json_encode($exc->getMessage()));
            $this->setHeader('validation', json_encode($exc->getValidationResult()));
        } catch (EDatabaseError $db ) {
            fvSite::$DB->rollback();            
            $this->setHeader('exception', json_encode($db->getMessage()));
        }
        fvSite::$DB->autoCommit(true);
        return $this->getRequest()->isXmlHttpRequest() ? self::$FV_AJAX_CALL : self::$FV_OK;
    }
    /**
    * Удаление фото
    * 
    */
    function executeDoDeleteImage()
    {
        $id = $this->getRequestParameter();
        $photo = CountryMediaManager::getInstance()->getByPk($id);
        if(!CountryMediaManager::getInstance()->isRootInstance($photo))
        {
            $this->setFlash('Ошибка при получении изображения', self::$FLASH_ERROR);
            return self::$FV_AJAX_CALL;
        }
        $photo->addField('oldImage', 'string', $photo->image);        
        if( $photo->delete() ) {
            $this->setFlash('Изображение удалено', self::$FLASH_SUCCESS);
        } else {
            $this->setFlash('Ошибка при удалении изображения', self::$FLASH_ERROR);
        }
            
        return self::$FV_AJAX_CALL;
    }
    /**
    * Установка главного фото
    * 
    */
    function executeDoSetMain()
    {
        $countryId = $this->getRequestParameter("hid","int",0);
        $mediaId = $this->getRequestParameter("id","int",0);
        try {
            $country = CountryManager::getInstance()->getByPk($countryId);
            if (!CountryManager::getInstance()->isRootInstance($country))
                throw new EUserMessageError("Страна не найдена");
                
            $media = CountryMediaManager::getInstance()->getByPk($mediaId);
            if (!CountryMediaManager::getInstance()->isRootInstance($media))
                throw new EUserMessageError("Фото не найдено");
            
            CountryMediaManager::getInstance()->clearIsMain($country);    
                
            $media->set("is_main",1);            
            if (!$media->save())
                throw new EUserMessageError("Ошибка сохранения данных");
            $this->setFlash('Выполнено', self::$FLASH_SUCCESS);
            
        } catch (EUserMessageError $exc) {
            $this->setFlash($exc->getMessage(), self::$FLASH_ERROR);
        }
        return self::$FV_AJAX_CALL;            
        
    }

}
