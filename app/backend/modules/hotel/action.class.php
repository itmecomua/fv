<?php

class HotelAction extends fvActionDictionary
{
   
    function __construct () 
    {
        parent::__construct(fvSite::$Layoult,HotelManager::getInstance());        
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
            $hotel = HotelManager::getInstance()->getByPk($id);
            if( !HotelManager::getInstance()->isRootInstance($hotel) )
                throw new EUserMessageError("Отель не найден");
            HotelMediaManager::getInstance()->saveMassPhoto($photo, $hotel);                        
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
        $photo = HotelMediaManager::getInstance()->getByPk($id);
        if(!HotelMediaManager::getInstance()->isRootInstance($photo))
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
        $hotelId = $this->getRequestParameter("hid","int",0);
        $mediaId = $this->getRequestParameter("id","int",0);
        try {
            $hotel = HotelManager::getInstance()->getByPk($hotelId);
            if (!HotelManager::getInstance()->isRootInstance($hotel))
                throw new EUserMessageError("Отель не найден");
                
            $media = HotelMediaManager::getInstance()->getByPk($mediaId);
            if (!HotelMediaManager::getInstance()->isRootInstance($media))
                throw new EUserMessageError("Фото не найдено");
            
            HotelMediaManager::getInstance()->clearIsMain($hotel);    
                
            $media->set("is_main",1);            
            if (!$media->save())
                throw new EUserMessageError("Ошибка сохранения данных");
            $this->setFlash('Выполнено', self::$FLASH_SUCCESS);
            
        } catch (EUserMessageError $exc) {
            $this->setFlash($exc->getMessage(), self::$FLASH_ERROR);
        }
        return self::$FV_AJAX_CALL;            
        
    }
    /**
    * Перегрузить список курортов
    * 
    */
    function executeReloadResort()
    {
        return self::$FV_NO_LAYOULT;
    }

}