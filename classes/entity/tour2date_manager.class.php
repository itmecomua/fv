<?php

require_once (fvSite::$fvConfig->get("path.entity") . 'tour2date.class.php') ;

 /**
 * Класс менеджер дат собственного тура
 * @author Korshenko Alexey
 * @since  2011/11/23
 * 
 */
class Tour2DateManager extends fvRootManager 
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
        if(count($data)) $where[] = 'date_start not in ("'.implode(",",$data).'")';
        $sql = "delete from {$this->getTableName()} where ".implode(" and ",$where);
        
        $res = @fvSite::$DB->query($sql);
        if(DB::isError($res)) throw new EUserMessageError("Ошибка сохранения дат туров" . $sql);        
        
        if(count($data))
        {
            $sql = "insert ignore into {$this->getTableName()}(tour_id,date_start) values ({$tour_id},'".implode("'),({$tour_id},'",$data)."')";
            $res = @fvSite::$DB->query($sql);
            if(DB::isError($res)) throw new EUserMessageError("Ошибка сохранения дат туров". $sql);                    
        }        
        return true;        
    }
}
