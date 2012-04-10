<?php

abstract class Dictionary extends fvRoot 
{

    protected $currentEntity = '';

    /**
     *  Получить название справочной сущности
     * @author Dmitriy Khoroshilov
     * @since 2011/07/26 
     *
     * @return string
    */  
    abstract public function getDictionaryName();
    
    function validateName( $value )
    {
        $valid = $this->doValidateEmpty($value);
        $this->setValidationResult('name', $valid, "Поле обязательное для заполнения");
        return $valid;
    }
    
    function validateUrl( $value )
    {
        $valid = $this->doValidateEmpty($value);
        if(!$valid)
        {
            $this->setValidationResult('url', $valid, "Поле обязательное для заполнения");
            return $valid;            
        }
        $valid = $this->doValidateUniq($value, 'url');
        $this->setValidationResult('url', $valid, "Поле должно быть уникальным");
        return $valid;
    }

    /**
     * Получить URL интерфейса редактирования
     * @author Dmitriy Khoroshilov
     * @since 2011/07/26 
     *
     * @return string
    */
    public function getBackendEditURL()
    {
        return fvSite::$fvConfig->get('dir_web_root') 
                . strtolower($this->currentEntity) 
                . "/edit/?id={$this->getPk()}";
    }
    
    /**
     * Получить URL удаления записи 
     * @author Dmitriy Khoroshilov
     * @since 2011/07/30 
     *
     * @return
    */
    public function getBackendDeleteURL()
    {
        return fvSite::$fvConfig->get('dir_web_root') 
                . strtolower($this->currentEntity)
                . "/delete/?id={$this->getPk()}";
    }
    
    /**
     * Получить имя обьекта для записи в лог 
     * @author Dmitriy Khoroshilov
     * @since 2011/07/26 
     *
     * @return string
    */
    public function getLogName()
    {
        return $this->getName();
    }
    
    /**
     * Получить сообщение лога 
     * @author Dmitriy Khoroshilov
     * @since 2011/07/26 
     *
     * @return
    */
    public function getLogMessage($operation) 
    {
        $message = "{$this->getDictionaryName()}. Справочник был ";
        switch ($operation) {
            case Log::OPERATION_INSERT: $message .= "создан ";break;
            case Log::OPERATION_UPDATE: $message .= "изменен ";break;
            case Log::OPERATION_DELETE: $message .= "удален ";break;
            case Log::OPERATION_ERROR: $message = "Произошла ошибка при операции с записью ";break;
        }
        $user = fvSite::$fvSession->getUser();
        $message = "в ".date("Y-m-d H:i:s").".";
        if (UserManager::getInstance()->isRootInstance($user))
            $message .= " Пользователь [{$user->getPk()}] {$user->getLogin()} ({$user->getFullName()})";
        return $message;
    }
    /**
     *  Вставить запись в лог
     * @author Dmitriy Khoroshilov
     * @since 2011/07/26 
     *
     * @return
    */
    public function putToLog($operation) 
    {
        $logMessage = LogManager::getInstance()->cloneRootInstance();   
        $logMessage->operation = $operation;
        $logMessage->object_type = $this->currentEntity;
        $logMessage->object_name = $this->getLogName();
        $logMessage->object_id = $this->getPk();
        $logMessage->manager_id = fvSite::$fvSession->getUser()
                                            ? fvSite::$fvSession->getUser()->getPk()
                                            : -1;
        $logMessage->message = $this->getLogMessage($operation);
        $logMessage->edit_link = $this->getBackendEditURL();
        $logMessage->save();        
    }
    /**
     * Получить вес 
     * @author Dmitriy Khoroshilov
     * @since 2011/07/30 
     *
     * @return int | string
    */
    public function getWeight()
    {
        return $this->hasField('weight') ? $this->weight : 'Не существующее поле weight';        
    }
    
    /**
    * Отображать?
    * @author Korshenko Alexey
    * @since  2011/11/18
    * 
    * @return bool
    */
    public function getIsShow()
    {
        return $this->hasField('is_show') ? (bool)$this->is_show : true;        
    }
    /**
    * Получить URL
    * @author Korshenko Alexey
    * @since  2011/11/18
    * 
    * @return string
    */
    public function getURL()
    {
        return $this->hasField('url') ? $this->url : 'no such field';        
    }
    /**
     * Получить имя текущей сушности 
     * @author Dmitriy Khoroshilov
     * @since 2011/07/30 
     *
     * @return string
    */
    public function getName()
    {
        return $this->hasField('name') ? $this->name : 'Не существующее поле name';
    }

}
