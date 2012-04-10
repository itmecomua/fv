<?php
/**
*   ContactsDay
*/

class Contacts extends fvRoot
{
    protected $currentEntity = ''; 
    
    function __construct () 
    {
        $this->currentEntity = __CLASS__;
        parent::__construct(fvSite::$fvConfig->get("entities.{$this->currentEntity}.fields"),
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.table_name"),
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.primary_key", "id"));
    }
      
/*
*  Методы получения полей
* 
*/
    
    /**
    * Получить заголовок для телефонов
    * @return int
    */
    public function getPhoneTitle()
    {                           
        return (string) $this->phonetitle;
    }      
    
    /**
    * Получить телефоны
    * @return string
    */
    public function getPhone()
    {                           
        return (string) $this->phone;
    } 
         
    /**
    * Получить заголовок для адреса
    * @return string
    */    
    public function getAddressTitle()
    {
        return (string) $this->addresstitle;
    }
    
    /**
    * Получить адрес
    * @return string
    */    
    public function getAddress()
    {
        return (string) $this->address;
    }

    /**
    * Отображать?
    * @return bool
    */
    public function isShow()
    {
        return (bool)$this->is_show;
    }
    
}
