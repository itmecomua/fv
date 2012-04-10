<?php

require_once (fvSite::$fvConfig->get("path.entity") . 'order.class.php') ;

class OrderManager extends DictionaryManager 
{
    
    const F_NAME = "F_NAME";
    const F_EMAIL = "F_EMAIL";
    const F_STATE = "F_STATE";
    const F_CTIME = "F_CTIME";

    const STATE_NOCHECK = 0;
    const STATE_CHECKED = 1;
    
    protected $listState = array(
        self::STATE_CHECKED => "Обработано",
        self::STATE_NOCHECK => "Не обработано"
    );
        
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
    
    public function getListBy(&$filter=array(),$order=array(),$page=0)
    {
        $f = (array) $filter;
        $where = array();
        if ($f[self::F_NAME]) 
        {
            $where[] = "inst.name like '%" . addslashes($f[self::F_NAME]) . "%'";
        }
        if ($f[self::F_EMAIL]) 
        {
            $where[] = "inst.email like '%" . addslashes($f[self::F_EMAIL]) . "%'";
        }
        if ($f[self::F_STATE] == null) {
            $f[self::F_STATE] = $filter[self::F_STATE] = -1;                                                
        }
        if ($f[self::F_STATE] > -1) 
        {
            $where[] = "inst.state = {$f[self::F_STATE]}";
        }      
        
         $where = count($where) > 0 ? " WHERE " . implode(" AND ",$where) : "";
         $orderBy = " ORDER BY ";
         switch ($order['field']) {
             case self::F_NAME: $orderBy .= "inst.name"; break;
             case self::F_EMAIL: $orderBy .= "inst.email"; break;
             case self::F_CTIME: $orderBy .= "inst.ctime"; break;
             case self::F_STATE: $orderBy .= "inst.state"; break;             
             default: $orderBy .="inst.ctime";
         }
         
         $orderBy .= " " . $order['direct'];
         $sql = " select inst.*
                    from {$this->getTableName()} inst
                    {$where}
                    {$orderBy}";
                    
        $addField=array();    
        $pager = new fvPager($this);        
        $list = $pager->paginateSQL($sql,null,$addField,$page);
        return $list;
    }
    public function getListState($default=null)
    {
        $listState = array();
        if (!is_null($default)) {
            $listState ["-1"] = $default;
            foreach ($this->listState as $k => $v) {
                $listState [$k] = $v;
            }            
        } else {
            $listState = $this->listState;
        }
        return $listState;
    }

}
