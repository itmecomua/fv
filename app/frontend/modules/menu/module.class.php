<?php

    class MenuModule extends fvModule
    {
        

        function __construct ()
        {
            $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
            parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"),
            fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"),
            fvSite::$Layoult);
        }

        function showIndex()
        {
            return __CLASS__;
        }        
        
       
        function showAdditional()
        {
            $where = array();
            $where[] = "parent_id is null";
            $where[] = "type_id = " . MenuManager::TYPE_VERTICAL;
            $where[] = "is_show = 1";
            $where = implode(" AND ", $where);
            $List = MenuManager::getInstance()->getAll($where, "weight asc");
            $this->__assign("List", $List);
            return $this->__display("additional.tpl");            
        }
        
        function showMain()
        {
            $where = array();
            $where[] = "type_id = " . MenuManager::TYPE_HORIZONTAL;
            $where[] = "is_show = 1";
            $where = implode(" AND ", $where);
            $List = MenuManager::getInstance()->getAll($where, "weight asc");
            $this->__assign("List", $List);
            return $this->__display("main.tpl");            
        }
    }

?>