<?php

require_once (fvSite::$fvConfig->get("path.entity") . 'country.class.php') ;

class CountryManager extends DictionaryManager 
{
    
    const F_NAME = "F_NAME";
    const F_WEIGHT = "F_WEIGHT";
    const F_IS_SHOW = "F_IS_SHOW";
    const F_IS_SHOW_PROMO = "F_IS_SHOW_PROMO";
        
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
        if ($f[self::F_NAME]) 
        {
            $where[] = "country.name like '%" . addslashes($f[self::F_NAME]) . "%'";
        }
      
        
         $where = count($where) > 0 ? " WHERE " . implode(" AND ",$where) : "";
         
         
         $orderBy = " ORDER BY ";
         switch ($order['field']) {
             case self::F_WEIGHT: $orderBy .= "country.weight"; break;
             case self::F_NAME: $orderBy .= "country.name"; break;
             case self::F_IS_SHOW: $orderBy .= "country.is_show"; break;
             case self::F_IS_SHOW_PROMO: $orderBy .= "country.is_show_promo"; break;             
             default: $orderBy .="country.id";
         }
         
         $orderBy .= " " . $order['direct'];
         $sql = " select country.*
                    from {$this->getTableName()} country
                    {$where}
                    {$orderBy}";
                    
        $addField=array();    
        $pager = new fvPager($this);        
        $list = $pager->paginateSQL($sql,null,$addField,$page);
        return $list;
    }

}
