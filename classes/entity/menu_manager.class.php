<?php

require_once (fvSite::$fvConfig->get("path.entity") . 'menu.class.php') ;

class MenuManager extends fvRootManager {
    //Дополнительное
    const TYPE_VERTICAL = 1;
    //Основное
    const TYPE_HORIZONTAL = 2;
    
    private $_types = array(
        self::TYPE_HORIZONTAL => "Основное",
        self::TYPE_VERTICAL => "Дополнительное",
    );	
	protected function __construct () {
	    $objectClassName = substr(__CLASS__, 0, -7);
	    if ($objectClassName == "") $objectClassName = "Manager";
	    $this->rootObj = new $objectClassName();
	}
	
    static function getInstance()
    {
        static $instance; 
                   
        $className = __CLASS__;
        
        if (!isset($instance)) {
            $instance = new self();
        }  
        return $instance;
    }
    
    /**
    * Получить тип меню
    * @author Nesterenko Nikita
    * @since 2011/07/12
    * @param int $type_id
    * @return string
    */
    public function getTypeMenu( $type_id = false)
    {
        return $type_id && in_array($type_id, array_keys($this->_types)) ? $this->_types[$type_id] : $this->_types;
    }
 
}