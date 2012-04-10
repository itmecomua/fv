<?php
 /**
 * Класс сущности стран собственного тура
 * @author Korshenko Alexey
 * @since  2011/11/23
 
 */
class Tour2Country extends fvRoot
{
    
    protected $currentEntity = '';  
    function __construct () 
    {
        $this->currentEntity = __CLASS__;
        parent::__construct(fvSite::$fvConfig->get("entities.{$this->currentEntity}.fields"),
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.table_name"),
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.primary_key", "id"));
    }
    
    /**
    * Получить объект страны
    * @author Korshenko Alexey
    * @since  2011/11/30
    * 
    * @return TourType
    */ 
    public function getCountry()
    {
        return $this->_getDictFieldBy("_country",CountryManager::getInstance(),"id={$this->country_id}");
    }
}
