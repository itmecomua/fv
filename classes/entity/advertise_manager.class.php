<?php
require_once (fvSite::$fvConfig->get("path.entity") . 'advertise.class.php') ;

class AdvertiseManager extends fvRootManager 
{
    
    const T_HEADER_LEFT = 1;
    const T_HEADER_RIGHT = 2;
    const T_CRB = 3;
    
    protected $listType = array(
        self::T_HEADER_LEFT => "Шапка – левая часть (до лого)",
        self::T_HEADER_RIGHT => "Шапка – правая часть (после лого)",
        self::T_CRB => "Центральная область стартовой страницы"
    );

    protected function __construct () 
    {        
        $objectClassName = substr(__CLASS__, 0, -7);        
        $this->_objectClassName = $objectClassName;
        $this->_className = __CLASS__;
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
    * Получить тип рекламного блока
    * 
    * @param int $typeId 
    * @return array | string
    */
    public function getListType($typeId=null,$def=null)
    {                
        if (!is_null($def)) {
            $listType = array(""=>$def);
            foreach ($this->listType as $key => $type) {
                $listType[$key]= $type;
            }    
        } else {
            $listType = $this->listType;
        }
        return !is_null($typeId) ? $listType[$typeId] : $listType;
    }
    
}
