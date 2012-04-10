<?php

require_once (fvSite::$fvConfig->get("path.entity") . 'resort.class.php') ;

class ResortManager extends DictionaryManager 
{
    
    const F_NAME = "F_NAME";
    const F_COUNTRY_ID = "F_COUNTRY_ID";
    const F_WEIGHT = "F_WEIGHT";
    const F_COUNTRY = "F_COUNTRY";
    
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
    public function getListBy($filter=array(),$order=array(),$page=0)
    {
        $f = (array) $filter;
        $where = array();
        if ($f[self::F_NAME]) {
            $where[] = "resort.name like '%" . addslashes($f[self::F_NAME]) . "%'";
        }
        if (intval($f[self::F_COUNTRY_ID]) > 0) {
            $where[] = "resort.country_id=" . intval($f[self::F_COUNTRY_ID]);
        }
        if (strlen($f[self::F_WEIGHT])> 0) {
            $where[] = "resort.weight='{$f[self::F_WEIGHT]}'";
        }
         $where = count($where) > 0 ? " WHERE " . implode(" AND ",$where) : "";
         $orderBy = " ORDER BY ";
         switch ($order['field']) {
             case self::F_WEIGHT: $orderBy .= "resort.weight"; break;
             case self::F_NAME: $orderBy .= "resort.name"; break;
             case self::F_COUNTRY: $orderBy .= "country.name"; break;
             default: $orderBy .="resort.id";
         }
         
         $orderBy .= " " . $order['direct'];
         $sql = " select resort.*
                    from {$this->getTableName()} resort
                    join " . CountryManager::getInstance()->getTableName() . " country
                        on (country.id=resort.country_id)
                    {$where}
                    {$orderBy}";
         
        $pager = new fvPager($this);        

        $list = $pager->paginateSQL($sql,null,$addField,$page);
        return $list;
    }

}
