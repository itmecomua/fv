<?php

class Resort extends Dictionary
{
    
    function __construct () 
    {
        $this->currentEntity = __CLASS__;
        parent::__construct(fvSite::$fvConfig->get("entities.{$this->currentEntity}.fields"),
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.table_name"),
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.primary_key", "id"));
    }
    
    public function getDictionaryName()
    {
        return 'Курорт';
    }

    public function getCountry()
    {
        return $this->_getDictFieldBy("_countryObj",CountryManager::getInstance(),$this->country_id);
    }
    
    public function getShortText()
    {
        return (string)$this->short_text;
    }
    
    public function getFullText()
    {
        return (string)$this->full_text;
    }
   
    
}
