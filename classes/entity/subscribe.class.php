<?php
/**
* Подписка
*/
class Subscribe extends Dictionary
{
    
    function __construct () 
    {
        $this->currentEntity = __CLASS__;
        parent::__construct(fvSite::$fvConfig->get("entities.{$this->currentEntity}.fields"),
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.table_name"),
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.primary_key", "id"));
    }
    public function validateEmail($value)
    {        
        if (!$this->doValidateEmail($value)) {
            $msg = "Некоректный email";
        } elseif (!$this->doValidateUniq($value,"email")) {
            $msg = "Такой email уже был записан";
        } else {
            $msg = "";
            $valid = true;
        }
        
        $this->setValidationResult("email",$valid,$msg);
        return $valid;        
    }
    public function validateName($value)
    {
        $valid = $this->doValidateEmpty($value);
        $this->setValidationResult("name",$valid,"Это обязательное поле");
        return $valid;
    }
    public function validatePhone($value)
    {
        $valid = $this->doValidateEmpty($value);
        $this->setValidationResult("name",$valid,"Это обязательное поле");
        return $valid;
    }
    public function validateCountry($value)
    {
        $valid = $this->doValidateEmpty($value);
        $this->setValidationResult("name",$valid,"Это обязательное поле");
        return $valid;
    }    
    public function getDictionaryName()
    {
        return 'Подписка';
    }
    public function getName()
    {
        return (string) $this->name;
    }
    /**
    * Получить email подписчика
    * 
    * @return string email
    */
    public function getEmail()
    {
        return (string)$this->email;
    }
    /**
    * Получить телефон 
    * 
    * @return string phone
    */
    public function getPhone()
    {
        return (string)$this->phone;
    }
    /**
    * Получить страну подписчика
    * 
    * @return string country
    */
    public function getCountry()
    {
        return (string)$this->country;
    }
    /**
    * Получить компанию подписчика
    * 
    * @return string company
    */
    public function getCompany()
    {
        return (string)$this->company;
    }
    /**
    * Получить должность подписчика
    * 
    * @return string post
    */
    public function getPost()
    {
        return (string)$this->post;
    }
    /**
    * Получить время создания подписки
    * 
    * @return string post
    */
    public function getCtime()
    {
        return (string)$this->post;
    }
    /**
    * Активная подписка?
    * 
    * @return bool is_active
    */
    public function isActive()
    {
        return (bool) $this->is_active;
    }
    
}