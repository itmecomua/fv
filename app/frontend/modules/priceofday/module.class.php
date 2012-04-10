<?php
/**
* Модуль отображения
* 
*/ 
class PriceOfDayModule extends fvModule
{
    function __construct ()
    {
            $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
            parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"),
            fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"),
            fvSite::$Layoult);            
    }

    /**
    * Отображение блока
    * 
    */ 
    function showIndex()
    {
        $pager = new fvPager(PriceOfDayManager::getInstance());
        $pager->setPaginatePerPage(12);
        $pager->paginate("is_show = 1","weight desc");            
        $this->__assign("PriceOfDay",$pager);
        return $this->__display("index.tpl");
    }
}
