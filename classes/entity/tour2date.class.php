<?php
 /**
 * Класс сущности дат собственного тура
 * @author Korshenko Alexey
 * @since  2011/11/23
 
 */
class Tour2Date extends fvRoot
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
    * Получить дату начала тура
    * @author Korshenko Alexey
    * @since  2011/11/30
    * 
    * @param string - формат даты
    * @return string
    */ 
    public function getDateStart($format = "d.m.Y")
    {
        return date($format,strtotime($this->date_start));
    }
}
