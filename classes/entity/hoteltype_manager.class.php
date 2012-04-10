<?php

require_once (fvSite::$fvConfig->get("path.entity") . 'hoteltype.class.php') ;

class HotelTypeManager extends DictionaryManager 
{
    const F_NAME = "F_NAME";
    const F_WEIGHT = "F_WEIGHT";
    
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
    public function getListBy(&$f=array(),$order=array(),$page=0)
    {

        $where = array();
        if ($f[self::F_NAME]) {
            $where[] = "ht.name like '%" . addslashes($f[self::F_NAME]) . "%'";
        }
        
        if (strlen($f[self::F_WEIGHT])> 0) {
            $where[] = "ht.weight='{$f[self::F_WEIGHT]}'";
        }
         $where = count($where) > 0 ? " WHERE " . implode(" AND ",$where) : "";
         $orderBy = " ORDER BY ";
         switch ($order['field']) {
             case self::F_WEIGHT: $orderBy .= "ht.weight"; break;
             case self::F_NAME: $orderBy .= "ht.name"; break;
             default: $orderBy .="ht.id";
         }
         
         $orderBy .= " " . $order['direct'];
         $sql = " select ht.*
                    from {$this->getTableName()} ht
                    {$where}
                    {$orderBy}";
                          
        $addField=array();    
        $pager = new fvPager($this);        
        $list = $pager->paginateSQL($sql,null,$addField,$page);        
                 
        return $list;
    }

}
