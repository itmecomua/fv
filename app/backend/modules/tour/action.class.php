<?php

class TourAction extends fvAction
{
    public $moduleName;
	function __construct ()
	{
	    $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
        parent::__construct(fvSite::$Layoult,TourManager::getInstance());
	}

	function executeIndex()
    {
         return (!fvRequest::getInstance()->isXmlHttpRequest()) ? self::$FV_OK : self::$FV_AJAX_CALL;
    }
    
    function executeEdit()
    {
         return (!fvRequest::getInstance()->isXmlHttpRequest()) ? self::$FV_OK : self::$FV_AJAX_CALL;
    }
    
    function executeSave()
	{
	 	if(!fvRequest::getInstance()->isXmlHttpRequest())
            return $this->redirect404();
            
        try 
        {            
            $id = $this->getRequest()->getRequestParameter('id','int',0);
            $update = $this->getRequest()->getRequestParameter('update');
            if ($id>0) 
            {
                $inst = TourManager::getInstance()->getByPk($id);                    
            }
            else 
            {
                $inst = TourManager::getInstance()->cloneRootInstance();
            }  
            if (!TourManager::getInstance()->isRootInstance($inst)) 
            {                
                fvResponce::getInstance()->setHeader('redirect',TourManager::getInstance()->getBackendListURL());
                throw new EUserMessageError("Такая запись не найдена в базе данных");
            }
            
            $inst->updateFromRequest($update);        
            
            if (!$inst->isValid()) 
            {
                throw new EUserMessageError("Ошибка при сохранении данных проверте правильность введенных данных",$inst);
            }
            $isNew = $inst->isNew();
            fvSite::$DB->autoCommit(false);
            if ($inst->save()) 
            {
                Tour2DateManager::getInstance()->saveTourData($inst->getPk(),$update['dates']);
                Tour2CountryManager::getInstance()->saveTourData($inst->getPk(),$update['Countries']);
                Tour2TypeManager::getInstance()->saveTourData($inst->getPk(),$update['TourType']);
                $this->setFlash("Данные успешно сохранены", self::$FLASH_SUCCESS);
            }
            else 
            { 
                throw new EUserMessageError("Ошибка сохранения в базу данных. Повторите попытку через несколько минут");
            }            
            
            if ($this->getRequestParameter('redirect')) 
            {
                fvResponce::getInstance()->setHeader('redirect', TourManager::getInstance()->getBackendListURL());
            } 
            elseif ($isNew)
            {
                fvResponce::getInstance()->setHeader('redirect',$inst->getBackendEditURL());
            }
            
            fvSite::$DB->commit();
            
        } 
        catch (EUserMessageError $exc) 
        {
            fvResponce::getInstance()->setHeader('X-JSON', json_encode($exc->getValidationResult()));
            $this->setFlash($exc->getMessage(),self::$FLASH_ERROR);
            fvSite::$DB->rollback();
        }
        catch(EDatabaseError $db )
        {
            $this->setFlash($db->getMessage(),self::$FLASH_ERROR);
            fvSite::$DB->rollback();
        }
        fvSite::$DB->autoCommit(true);
        return self::$FV_AJAX_CALL;
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
            $tour = TourManager::getInstance()->getByPk($id);
            if( !TourManager::getInstance()->isRootInstance($tour) )
                throw new EUserMessageError("Страна не найдена");                
            TourMediaManager::getInstance()->saveMassPhoto($photo, $tour);                        
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
        $photo = TourMediaManager::getInstance()->getByPk($id);
        if(!TourMediaManager::getInstance()->isRootInstance($photo))
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
        $tourId = $this->getRequestParameter("hid","int",0);
        $mediaId = $this->getRequestParameter("id","int",0);
        try {
            $tour = TourManager::getInstance()->getByPk($tourId);
            if (!TourManager::getInstance()->isRootInstance($tour))
                throw new EUserMessageError("Страна не найдена");
                
            $media = TourMediaManager::getInstance()->getByPk($mediaId);
            if (!TourMediaManager::getInstance()->isRootInstance($media))
                throw new EUserMessageError("Фото не найдено");
            
            TourMediaManager::getInstance()->clearIsMain($tour);    
                
            $media->set("is_main",1);            
            if (!$media->save())
                throw new EUserMessageError("Ошибка сохранения данных");
            $this->setFlash('Выполнено', self::$FLASH_SUCCESS);
            
        } catch (EUserMessageError $exc) {
            $this->setFlash($exc->getMessage(), self::$FLASH_ERROR);
        }
        return self::$FV_AJAX_CALL;            
        
    }
    function executeImportTour()
    {
        return self::$FV_NO_LAYOULT;
    }
    function executeCheckImport()
    {
        return self::$FV_NO_LAYOULT;
    }
  }   