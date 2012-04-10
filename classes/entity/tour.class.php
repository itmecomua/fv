<?php
 /**
 * Класс сущности собственного тура
 * @author Korshenko Alexey
 * @since  2011/11/23
 
 */
class Tour extends fvRoot implements iLogger
{
    protected $currentEntity = '';
    
    function __construct () 
    {
        $this->currentEntity = __CLASS__;
        parent::__construct(fvSite::$fvConfig->get("entities.{$this->currentEntity}.fields"),
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.table_name"),
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.primary_key", "id"));
    }
    
    public function getBackendEditURL()
    {
        return fvSite::$fvConfig->get('dir_web_root') 
                . strtolower($this->currentEntity) 
                . "/edit/?id={$this->getPk()}";
    }
    
    public function getBackendDeleteURL()
    {
        return fvSite::$fvConfig->get('dir_web_root') 
                . strtolower($this->currentEntity)
                . "/delete/?id={$this->getPk()}";
    }
    
    public function getDictionaryName()
    {
        return "Тур";
    }
    
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
    * Получить короткое описание
    * @author Korshenko Alexey
    * @since  2011/11/23
    * 
    * @return string
    */ 
    public function getShortText()
    {
        return (string)$this->short_text;
    }
    
    /**
    * Получить полное описание
    * @author Korshenko Alexey
    * @since  2011/11/23
    * 
    * @return string
    */ 
    public function getFullText()
    {
        return (string)$this->full_text;
    }
    
    /**
    * Получить кол-во просмотров
    * @author Korshenko Alexey
    * @since  2011/11/23
    * 
    * @return int
    */ 
    public function getCntView()
    {
         return  (int)$this->cnt_view;
    }
    
    /**
    * Получить кол-во ночей
    * @author Korshenko Alexey
    * @since  2011/11/23
    * 
    * @return int
    */ 
    public function getDuration()
    {
        return (int)$this->duration;
    }
    
    /**
    * Получить цену
    * @author Korshenko Alexey
    * @since  2011/11/23
    * 
    * @return int
    */ 
    public function getPrice()
    {
        return (int)$this->price_from;
    }
    
    /**
    * Получить валюту
    * @author Korshenko Alexey
    * @since  2011/11/23
    * 
    * @return string
    */ 
    public function getCurrency()
    {
        return trim($this->currency);
    }
    
    /**
    * Получить вес 
    * @author Korshenko Alexey
    * @since  2011/11/23
    * 
    * @return int
    */  
    public function getWeight()
    {
        return (int)$this->weight;        
    }
    
    /**
    * Получить URL просмотра информации о туре
    * @author Korshenko Alexey
    * @since  2011/11/23
    * 
    * @return string
    */ 
    public function getViewURL()
    {
        return "/tours/view/".$this->getURL();
    }
    
    /**
    * Получить URL
    * @author Korshenko Alexey
    * @since  2011/11/23
    * 
    * @return string
    */
    public function getURL()
    {
        return trim($this->url);        
    }
    
    public function getImportURL()
    {
        return $this->import_url;
    }    
    
    
    /**
    * Получить имя текущей сушности
    * @author Korshenko Alexey
    * @since  2011/11/23
    * 
    * @return string
    */    
    public function getName()
    {
        return trim($this->name);
    }       
    
    /**
    * Увеличить счётчик просмотров
    * @author Korshenko Alexey
    * @since  2011/11/23
    * 
    */ 
    public function setCountView()
    {
        $sql = "update ".TourManager::getInstance()->getTableName()." set cnt_view = cnt_view + 1 where id = ".$this->getPk();        
        $res = @fvSite::$DB->query($sql);
    }
    
    /**
    * Отображать тур или нет
    * @author Korshenko Alexey
    * @since  2011/11/23
    * 
    * @return bool
    */ 
    public function isShow()
    {
        return (bool)$this->is_show;
    }
    
    /**
    * Получить даты туров
    * @author Korshenko Alexey
    * @since  2011/11/30
    * 
    * @param string delimiter
    * @param string format
    * 
    * @return array of Tour2Date
    */ 
    public function getDates($delimiter=null,$formatDate=null)
    {
        
        if($this->hasField("dates") == false) 
            $this->addField("dates","array",(array)Tour2DateManager::getInstance()->getAll("tour_id=?","date_start asc",false,array(intval($this->getPk()))));
        if (is_null($delimiter)) {
            return $this->get("dates");    
        } else {
            $impl = array();
            foreach($this->get("dates") as $date) {
                $impl[]= is_null($formatdate)?$date->getDateStart():$date->getDateStart($formatDate);
            }
            $impl = implode($delimiter,$impl);
            return $impl;
        }
        
        
    }
    
    /**
    * Получить типы туров
    * @author Korshenko Alexey
    * @since  2011/11/30
    * 
    * @return array of Tour2Date
    */ 
    public function getTourTypes()
    {
        if($this->hasField("types") == false) 
            $this->addField("types","array",(array)Tour2TypeManager::getInstance()->getAll("tour_id=?","id asc",false,array(intval($this->getPk()))));
        return $this->get("types");
        
    }
    
    /**
    * Получить типы туров
    * @author Korshenko Alexey
    * @since  2011/11/30
    * 
    * @return array of Tour2Date
    */ 
    public function getCountries()
    {
        if($this->hasField("countries") == false) 
            $this->addField("countries","array",(array)Tour2CountryManager::getInstance()->getAll("tour_id=?","id asc",false,array(intval($this->getPk()))));
        return $this->get("countries");        
    }
    
    /**
    * Если ли данная страна у тура
    * @author Korshenko Alexey
    * @since  2011/11/30
    * 
    * @param int - ID страны 
    * @return bool
    */ 
    public function hasCountry($country_id)
    {
        $countries = (array)$this->getCountries();
        if($this->hasField("countries_ids") == false)  
        {
            $output = array();
            foreach($countries as $key=>$val)
            {
                $output[$val->country_id] = $val->country_id;
            }            
            $this->addField("countries_ids","array",$output);
        }        
        $ids = (array)$this->get("countries_ids");        
        return in_array($country_id,$ids);
    }
    
    /**
    * Если ли данный тип тура у текущего тура
    * @author Korshenko Alexey
    * @since  2011/11/30
    * 
    * @param int - ID типа тура 
    * @return bool
    */ 
    public function hasTourType($type_id)
    {
        $tourypes = (array)$this->getTourTypes();
        if($this->hasField("types_ids") == false)  
        {
            $output = array();
            foreach($tourypes as $key=>$val)
            {
                $output[$val->type_id] = $val->type_id;
            }            
            $this->addField("types_ids","array",$output);
        }        
        $ids = (array)$this->get("types_ids");        
        return in_array($type_id,$ids);
    }
     /**
    * Получить все фото тура
    * 
    */
    public function getPhoto()
    {
        return (array)TourMediaManager::getInstance()->getAll("tour_id='{$this->getPk()}'", "weight asc");
    }
    
    /**
    * Получить фото тура для галереи
    * 
    */
    public function getGalleryPhoto()
    {
        return (array)TourMediaManager::getInstance()->getAll("tour_id='{$this->getPk()}'", "weight asc");
    }
    
    /**
    * Получить main фото
    * 
    */
    public function getMainPhoto()
    {
        if(false == $this->hasField("mainPhoto"))
        {
            $list = (array)TourMediaManager::getInstance()->getAll("tour_id='{$this->getPk()}'", "is_main desc, weight asc","0,1");
            $ex = null;
            if(count($list) > 0) $ex = current($list);
            if(!TourMediaManager::getInstance()->isRootInstance($ex)) {
                $c = array();
                foreach ((array)$this->getCountries() as $iCountry) {
                    $c[] = $iCountry->country_id;
                }                
                if (count($c)) {
                    $c = implode(",",$c);
                    $ex = current(CountryMediaManager::getInstance()->getAll("country_id in ({$c}) and type_id=".CountryMediaManager::MEDIATYPE_ICON,"rand()","0,1"));    
                    
                }
                if (!CountryMediaManager::getInstance()->isRootInstance($ex)) {
                    $ex = TourMediaManager::getInstance()->cloneRootInstance();    
                }                
            }
            $this->addField("mainPhoto","object",$ex);        
        }
        return $this->mainPhoto;
    }
    
}
