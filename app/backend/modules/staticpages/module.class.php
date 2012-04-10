<?php

class StaticPagesModule extends fvModule {

    function __construct () 
    {
        $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
        parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"), 
        fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"), 
        fvSite::$Layoult);
    }

    function showIndex() 
    {
        $pager = new fvPager( StaticPagesManager::getInstance() );
        $this->__assign('StaticPages', $pager->paginate());
        return $this->__display('sp_list.tpl');
    }

    function showEdit() 
    {
        $id = $this->getRequestParameter();
        $StaticPage = StaticPagesManager::getInstance()->getByPk($id, true);
          
        $new = StaticPagesManager::getInstance()->cloneRootInstance();
        $this->__assign(array(
            'StaticPage' => $StaticPage,
        ));
        return $this->__display('sp_edit.tpl');
        
    }
}

?>