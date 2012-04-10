<?php
require_once (fvSite::$fvConfig->get("path.entity") . 'codedictionary.class.php') ;
require_once (fvSite::$fvConfig->get("path.entity") . 'codedictionary.class.php') ;

class CodeDictionaryManager extends DictionaryManager 
{
   
    const POSITION_HEADER       = 1;
    const POSITION_FOOTER       = 2;
    const POSITION_COL1         = 3;
    const POSITION_COL2         = 4;
    const POSITION_ITTOUR       = 5;
    const POSITION_ITTOURSCRIPT = 6;
    
    
    const INDEX = "getCode";
    
    private $_positions;
    protected function __construct () 
    {        
        $objectClassName = substr(__CLASS__, 0, -7);        
        $this->_objectClassName = $objectClassName;
        $this->_className = __CLASS__;
        $this->rootObj = new $objectClassName();
        
        $this->_positions = array(
            self::POSITION_HEADER       => "раздел документа HEAD",
            self::POSITION_FOOTER       => "перед закрывающим BODY",
            self::POSITION_COL1         => "Коллекция кодов 1",
            self::POSITION_COL2         => "Коллекция кодов 2",
            self::POSITION_ITTOUR       => "Контейнер ItTour",
            self::POSITION_ITTOURSCRIPT => "Код ItTourScript",
            
            
        );
        
    }
    
    static function getInstance()
    {
        static  $instance; 
        if (!isset($instance))
            $instance = new CodeDictionaryManager;
        return $instance;
    }

    
    public function getListBy($filter=array(),$order=array(),$page=0)
    {
        $f = (array) $filter;
        $where = array();
        
         $where = count($where) > 0 ? " WHERE " . implode(" AND ",$where) : "";
         $orderBy = " ORDER BY ";
         switch ($order['field']) {             
             default: $orderBy .="code.id";
         }
         
         $orderBy .= " " . $order['direct'];
         $sql = " select code.*
                    from {$this->getTableName()} code
                    {$where}
                    {$orderBy}";
         
        $pager = new fvPager($this);        

        $list = $pager->paginateSQL($sql,null,$addField,$page);
        
        return $list;
    }
    
    public function getPosition($pid = false)
    {
        return $pid && $this->_positions[$pid] ? $this->_positions[$pid] : $this->_positions;
    }
    
    public function __call($name, $params)
    {                     

        if( strpos($name, self::INDEX) !== 0 )                                     
            return parent::__call($name, $params);
        //$cache = fvCache::getInstance()->getCache(__CLASS__.$name, 3600);        
        //if( $cache ) return $cache;
        $positionName = "POSITION_" . strtoupper( str_replace(self::INDEX, "", $name) );
        $position_id = $this->getConst($positionName);
        $list = (array)$this->htmlSelect('code',null, "is_active=1 AND position_id='{$position_id}'");
        $list = fvSite::$DB->getAssoc("SELECT techname, code FROM " . $this->getTableName() . " WHERE is_active=1 AND position_id='{$position_id}'");
        foreach((array)$list as $key=>$val)
        {
          $data  .= "<div class='{$key}'>{$val}</div>";
        }
        //$data = implode($data, $list);
        //fvCache::getInstance()->setCache($data, __CLASS__,$name);
        return $data;
//return $name;
// echo $name;

    }          
    

    public function __get($name)
    {                     
    
        if( strpos($name, self::INDEX) !== 0 )                                     
            return parent::__call($name, $params);
        //$cache = fvCache::getInstance()->getCache(__CLASS__.$name, 3600);        
        //if( $cache ) return $cache;
        $positionName = "POSITION_" . strtoupper( str_replace(self::INDEX, "", $name) );
        $position_id = $this->getConst($positionName);
        $list = (array)$this->htmlSelect('code',null, "is_active=1 AND position_id='{$position_id}'");
        $list = fvSite::$DB->getAssoc("SELECT techname, code FROM " . $this->getTableName() . " WHERE is_active=1 AND position_id='{$position_id}'");
        foreach((array)$list as $key=>$val)
        {
          $data  .= "<div class='{$key}'>{$val}</div>";
        }
        //$data = implode($data, $list);
        //fvCache::getInstance()->setCache($data, __CLASS__,$name);
        return $data;
    }        
    
    

}
