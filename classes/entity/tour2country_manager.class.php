<?php

require_once (fvSite::$fvConfig->get("path.entity") . 'tour2country.class.php') ;

 /**
 * Класс менеджер стран собственного тура
 * @author Korshenko Alexey
 * @since  2011/11/23
 * 
 */
class Tour2CountryManager extends fvRootManager 
{
    
    protected function __construct () 
    {
        $objectClassName = substr(__CLASS__, 0, -7);        
        $this->rootObj = new $objectClassName();        
    }
    
    static function getInstance()
    {
        static $instance; 
        if (!isset($instance))
            $instance = new self();
        return $instance;
    }
    
    /**
    * Сохранить свзязанные с туров данные
    * @author Korshenko Alexey
    * @since  2011/11/30
    * 
    * @param mixed
    * @exception EUserMessageError
    * @return bool
    */ 
    public function saveTourData($tour_id, $data)
    {
        $where[] = 'tour_id = '.$tour_id;
        if(count($data)) $where[] = 'country_id not in ("'.implode(",",$data).'")';
        $sql = "delete from {$this->getTableName()} where ".implode(" and ",$where);
        
        $res = @fvSite::$DB->query($sql);
        if(DB::isError($res)) throw new EUserMessageError("Ошибка удаления стран туров");        
        
        if(count($data))
        {
            $sql = "insert ignore into {$this->getTableName()}(tour_id,country_id) values ({$tour_id},".implode("),({$tour_id},",$data).")";
            $res = @fvSite::$DB->query($sql);
            if(DB::isError($res)) throw new EUserMessageError("Ошибка сохранения стран туров");                    
        }        
        return true;        
    }
}
