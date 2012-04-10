<?php
require_once (fvSite::$fvConfig->get("path.entity") . 'tourtype.class.php') ;

class TourTypeManager extends DictionaryManager 
{
    const F_NAME = "F_NAME"; 
    const F_WEIGHT = "F_WEIGHT"; 
    const F_IS_SHOW = "F_IS_SHOW"; 
   
    const URL_ALL = "all";
     
    protected function __construct () 
    {
        $objectClassName = substr(__CLASS__, 0, -7);
        $this->_objectClassName = $objectClassName;
        $this->rootObj = new $objectClassName();        
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
            $where[] = "inst.name like '%" . addslashes($f[self::F_NAME]) . "%'";
        }
        $where = count($where) > 0 ? " WHERE " . implode(" AND ",$where) : "";
        $orderBy = " ORDER BY ";
        switch ($order['field']) {
             case self::F_NAME: $orderBy .= "inst.name"; break;
             case self::F_WEIGHT: $orderBy .= "inst.weight"; break;
             case self::F_IS_SHOW: $orderBy .= "inst.is_show"; break;
             default: $orderBy .="inst.id";
        }
         
        $orderBy .= " " . $order['direct'];
        $sql = " select inst.*
                    from {$this->getTableName()} inst
                    {$where}
                    group by inst.id
                    {$orderBy}";
         
        $pager = new fvPager($this);        

        $list = $pager->paginateGroupSQL($sql,null,$addField,$page);
        return $list;
    }

            
}