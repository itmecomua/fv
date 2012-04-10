<?php

require_once (fvSite::$fvConfig->get("path.entity") . 'hotel.class.php') ;

/**
* Менеджер сущностей отелей
*/
class HotelManager extends DictionaryManager 
{
    /**
    * константы фильтра
    */
    const F_NAME = "F_NAME";    
    const F_RESORT = "F_RESORT";    
    const F_COUNTRY = "F_COUNTRY";    
    const F_HOTEL_TYPE = "F_HOTEL_TYPE";    
 
    protected function __construct () 
    {
        $objectClassName = substr(__CLASS__, 0, -7);        
        $this->rootObj = new $objectClassName();
        $this->_objectClassName = $objectClassName;
        $this->_className = __CLASS__;
    }
    
    static function getInstance()
    {
        static $instance; 
        if (!isset($instance))
            $instance = new self();
        return $instance;
    }
    /**
    * Получить список 
    * 
    * @param mixed $filter фильтр 
    * @param mixed $order  сортировка 
    * @param mixed $page   страница
    */
    public function getListBy($filter=array(),$order=array(),$page=0)
    {
        $f = (array) $filter;
        $where = array();
        if ($f[self::F_NAME]) 
        {
            $where[] = "hotel.name like '%" . addslashes($f[self::F_NAME]) . "%'";
        }              
         $where = count($where) > 0 ? " WHERE " . implode(" AND ",$where) : "";
         $orderBy = " ORDER BY ";
         switch ($order['field']) 
         {
             case self::F_RESORT: $orderBy .= "resort.name"; break;
             case self::F_HOTEL_TYPE: $orderBy .= "hoteltype.name"; break;
             case self::F_COUNTRY: $orderBy .= "country.name"; break;
             case self::F_NAME: $orderBy .= "hotel.name"; break;
             default: $orderBy .="hotel.id";
         }
         
         $orderBy .= " " . $order['direct'];
         $sql = " select hotel.*
                    from {$this->getTableName()} hotel
                    left join  " . ResortManager::getInstance()->getTableName() . " resort
                        on (hotel.resort_id=resort.id)
                    left join " . CountryManager::getInstance()->getTableName() . " country
                        on (country.id=hotel.country_id)
                    left join " . HotelTypeManager::getInstance()->getTableName()  . " hoteltype
                        on (hoteltype.id=hotel.hotel_type_id)
                        
                    {$where}
                    {$orderBy}";
                    
        $addField=array();    
        $pager = new fvPager($this);        
        $list = $pager->paginateSQL($sql,null,$addField,$page);
        return $list;
    }

}