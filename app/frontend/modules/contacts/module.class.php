<?php
/**
* Модуль отображения
* 
*/ 
class ContactsModule extends fvModule
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
        $pager = new fvPager(ContactsManager::getInstance());
        $pager->setPaginatePerPage(12);
        $pager->paginate("is_show = 1", null);            
        $this->__assign("StorageObj",$pager);
        return $this->__display("index.tpl");
    }
    
    function showUpsidedown()
    {
        $pager = new fvPager(ContactsManager::getInstance());
        $pager->setPaginatePerPage(12);
        $pager->paginate("is_show = 1", null);            
        $this->__assign("StorageObj",$pager);
        return $this->__display("upsidedown.tpl");
    }    
}
