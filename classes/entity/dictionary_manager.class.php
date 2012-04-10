<?php

require_once (fvSite::$fvConfig->get("path.entity") . 'dictionary.class.php') ;
       
abstract class DictionaryManager extends fvRootManager 
{
    /**
    * Имя класа обекта
    * 
    * @var staring
    */
    protected $_objectClassName = null;
    /**
    * Имя класса менеджера
    * 
    * @var mixed
    */
    protected $_className = null;
    
    /**
     * Получить ссылку на интерфейс просмотра списка 
     * @author Dmitriy Khoroshilov
     * @since 2011/07/29 
     *
     * @return string
    */
    public function getBackendListURL()
    {
        return fvSite::$fvConfig->get('dir_web_root') . strtolower($this->_objectClassName);               
    }
    /**
     *  Получить массив доступных значений веса справочной записи
     * @author Dmitriy Khoroshilov
     * @since 2011/07/30 
     *
     * @return array
    */
    public function getListWeight($default=null)
    {
        $arr = range(-20,20,1);
        $arr = array_combine($arr,$arr);
        if (!is_null($default)) {
            $ret = array(''=>$default);
            foreach ($arr as $k=>$v) 
               $ret[$k] = $v;
            return $ret;
        } else return $arr;
    }
    
}
