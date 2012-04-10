<?php

class IconModule extends fvModule 
{
    function __construct () 
    {
        $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
        parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"), 
        fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"), 
        fvSite::$Layoult);
    }

    function showIndex($typeId=null,$template="index.tpl") 
    {  
       $typeId = intval($typeId);
       $where = array("is_show=1");
       
       $where = count($where)>0?implode(" and ",$where):null; 
       $list = IconManager::getInstance()->getAll($where, "weight asc");
       
       $this->__assign('list',$list);
       return count($list)>0 ? $this->__display($template) : "";
    }   
}
