<?php

require_once (fvSite::$fvConfig->get("path.entity") . 'subscribe.class.php') ;

/**
* Подписка. Менеджер сущностей.
*/
class SubscribeManager extends DictionaryManager 
{

    const F_EMAIL = "F_EMAIL";
    const F_ISACTIVE = "F_ISACTIVE";
    const F_CTIME = "F_CTIME";
    
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
    public function getListBy($filter=array(),$order=array(),$page=0,$isPaginate=true)
    {
        $f = (array) $filter;
        $where = array();
        if ($f[self::F_EMAIL]) 
        {
            $where[] = "subscr.email like '%" . addslashes($f[self::F_EMAIL]) . "%'";
        }
        if ($f[self::F_ISACTIVE]>-1) 
        {
            $where[] = "subscr.is_active='{$f[self::F_ISACTIVE]}'";
        }         
        $where = count($where) > 0 ? " WHERE " . implode(" AND ",$where) : "";
        $orderBy = " ORDER BY ";
        switch ($order['field']) {
             case self::F_EMAIL: $orderBy .= "subscr.email"; break;
             case self::F_CTIME: $orderBy .= "subscr.ctime"; break;             
             default: $orderBy .="subscr.ctime";
        }
         
        $orderBy .= " " . $order['direct'];
        $sql = " select subscr.*
                    from {$this->getTableName()} subscr
                    {$where}
                    {$orderBy}";
         
        $pager = new fvPager($this);        
        if ($isPaginate) {
            $list = $pager->paginateSQL($sql,null,$addField,$page);    
        } else {
            $list = $this->getObjectBySQL($sql);
        }
        
        return $list;
    }
   /**
    * Получить поля для выгрузки данных в CSV
    * 
    * @return array массив имя поля => метка
    */
    public function getFieldListCSV()
    {
        return array("email"=>"Email",
                     "name"=>"Имя",
                     "phone"=>"Phone",
                     "country"=>"Страна",
                     "company"=>"Компания",
                     "post"=>"Должность");
    }


}
