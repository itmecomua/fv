<?php

abstract class fvActionDictionary extends fvAction 
{
    /**
    * Менеджер сущностей справочника
    * 
    * @var mixed
    */
    protected $_manager;
    
    public function __construct($currPage,$currManager)
    {        
        $this->_manager = $currManager;
        parent::__construct($currPage);
    }
    /**
     * Получить менеджера сущностей
     * @author Dmitriy Khoroshilov
     * @since 2011/07/29 
     *
     * @return object
    */
    protected function getManager()
    {
        return $this->_manager;
    }
    /**
     * Список справочных данных
     * @author Dmitriy Khoroshilov
     * @since 2011/07/29 
     *
     * @return
    */  
    public function executeIndex() 
    {
        return fvRequest::getInstance()->isXmlHttpRequest()
                    ? self::$FV_AJAX_CALL 
                    : self::$FV_OK;
    }
    /**
     * Интерфейс редактирования 
     * @author Dmitriy Khoroshilov
     * @since 2011/07/29 
     *
     * @return
    */    
    function executeEdit() 
    {
        return fvRequest::getInstance()->isXmlHttpRequest()
                    ? self::$FV_AJAX_CALL 
                    : self::$FV_OK;
    }
    /**
     * Выполнить сохранение 
     * @author Dmitriy Khoroshilov
     * @since 2011/07/29 
     *
     * @return
    */
    function executeSave() 
    {                
        try 
        {            
            $id = $this->getRequest()->getRequestParameter('id','int',0);
            $update = $this->getRequest()->getRequestParameter('update');
            if ($id>0) 
            {
                $inst = $this->getManager()->getByPk($id);                    
            }
            else 
            {
                $inst = $this->getManager()->cloneRootInstance();
            }  
            if (!$this->getManager()->isRootInstance($inst)) 
            {                
                fvResponce::getInstance()->setHeader('redirect',$this->getManager()->getBackendListURL());
                throw new EUserMessageError("Такая запись не найдена в базе данных");
            }
            
            $inst->updateFromRequest($update);        
            
            if (!$inst->isValid()) {
                throw new EUserMessageError("Ошибка при сохранении данных проверте правильность введенных данных",$inst);
            }
            $isNew = $inst->isNew();
            if ($inst->save()) {
                $this->setFlash("Данные успешно сохранены", self::$FLASH_SUCCESS);
            } else { 
                throw new EUserMessageError("Ошибка сохранения в базу данных. Повторите попытку через несколько минут");
            }            
            
            if ($this->getRequestParameter('redirect')) 
            {
                fvResponce::getInstance()->setHeader('redirect', $inst->getManager()->getBackendListURL());
            } 
            elseif ($isNew)
            {
                fvResponce::getInstance()->setHeader('redirect',$inst->getBackendEditURL());
            }
            
        } catch (EUserMessageError $exc) 
        {
            fvResponce::getInstance()->setHeader('X-JSON', json_encode($exc->getValidationResult()));
            $this->setFlash($exc->getMessage(),self::$FLASH_ERROR);
        }  catch(EDatabaseError $db )
        {
            $this->setFlash($db->getMessage(),self::$FLASH_ERROR);
        }
        
        return fvRequest::getInstance()->isXmlHttpRequest()
            ? self::$FV_AJAX_CALL 
            : self::$FV_OK;

    }
    /**
     *  Удаление 
     * @author Dmitriy Khoroshilov
     * @since 2011/07/29 
     *
     * @return
    */
    function executeDelete() 
    {
        try 
        {            
            $id = $this->getRequest()->getRequestParameter('id','int',0);            
            if ($id > 0) 
            {
                $inst = $this->getManager()->getByPk($id);                    
            }
            else 
            {
                $inst = $this->getManager()->cloneRootInstance();
            }  
            if (!$this->getManager()->isRootInstance($inst)) 
            {                
                fvResponce::getInstance()->setHeader('redirect',$this->getManager()->getBackendListURL());
                throw new EUserMessageError("Такая запись не найдена в базе данных");
            }
            
            if (!$inst->delete()) 
            {
                throw new EUserMessageError("Ошибка удаления из базы данных. Повторите попытку через несколько минут");
            }
            $this->setFlash("Данные успешно удалены", self::$FLASH_SUCCESS);                
            fvResponce::getInstance()->setHeader('redirect',$this->getManager()->getBackendListURL());            
        }
        catch (EUserMessageError $exc) 
        {
            fvResponce::getInstance()->setHeader('X-JSON', json_encode($exc->getValidationResult()));
            $this->setFlash($exc->getMessage(),self::$FLASH_ERROR);
        }
        
        return fvRequest::getInstance()->isXmlHttpRequest()
            ? self::$FV_AJAX_CALL 
            : self::$FV_OK;
    }
    
}
