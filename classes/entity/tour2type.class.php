<?php
 /**
 * Класс сущности типов тура собственного тура
 * @author Korshenko Alexey
 * @since  2011/11/23
 
 */
class Tour2Type extends fvRoot
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
    * Получить объект типа тура
    * @author Korshenko Alexey
    * @since  2011/11/30
    * 
    * @return TourType
    */ 
    public function getTourType()
    {
        return $this->_getDictFieldBy("_tourtype",TourTypeManager::getInstance(),"id={$this->type_id}");
    }
}
