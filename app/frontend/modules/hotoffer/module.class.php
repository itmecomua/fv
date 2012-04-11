<?php

    /**
    * Модуль отображения
    * 
    */ 
    class HotOfferModule extends fvModule
    {
        function __construct ()
        {
            $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
            parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"),
            fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"),
            fvSite::$Layoult);            
        }

        /**
        * Отображение списка 
        * 
        */ 
        function showIndex()
        {
            return $this->showList();
        }
        
        /**
        * Отображение списка 
        * 
        */ 
        function showList()
        {
            $pager = new fvPager(HotOfferManager::getInstance());
            $pager->setPaginatePerPage(12);
            $pager->paginate("is_show = 1","weight asc, name asc");            
            $this->__assign("HotOffer",$pager);
            return $this->__display("list.tpl");
        }
        
   
    }