<?php

class TourModule extends fvModule
{
    protected $instance;
    protected $moduleName;
    function __construct () 
    {
        $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
        parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"), 
        fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"), 
        fvSite::$Layoult);
        $this->instance = TourManager::getInstance();
    }

    function showIndex()
    {
        $search = $this->getRequest()->getRequestParameter('search');
        $order = $this->getRequest()->getRequestParameter('order');
        $page = $this->getRequestParameter('page');
        
        
        if ($search['_clear']=="1") 
        {
            $search = $order = array();
            $page = 0;
        }
            
        $sessKey = $this->moduleName.__FUNCTION__.'filter';
                                    
        if (is_null($search) && is_null($order)) 
        {
            $filter = fvSite::$fvSession->get($sessKey);
            $search = (array) $filter['search'];
            $order = (array) $filter['order'];
            $page = $page ? $page : (int) $filter['page'];            
        }
        else 
        {
            $page = $page ? $page : 0;
            fvSite::$fvSession->set($sessKey,array('search'=>$search,'page'=>$page,'order'=>$order));
        }
        
        $list = $this->instance->getListBy($search,$order,$page);        
                    
        $this->__assign('search', $search);
        $this->__assign('order', $order);
        $this->__assign('list', $list);
        $this->__assign('manager', $this->instance);
        return $this->__display('index.tpl');
    }
    
    function showEdit() 
    {
        $id = $this->getRequestParameter();
        $inst = $this->instance->getByPk($id,true);
        $this->__assign('inst',$inst);
        $this->__assign('manager', $this->instance);
        $this->__assign("listWeight",TourManager::getInstance()->getListWeight());
        $this->__assign("listDuration",TourManager::getInstance()->getListDuration());
        $this->__assign("listVisible",array("0"=>"Нет","1"=>"Да"));
        $this->__assign("countires",CountryManager::getInstance()->getAll(false,"name asc"));
        $this->__assign("types",TourTypeManager::getInstance()->getAll(false,"name asc"));
        return $this->__display('edit.tpl');        
    }
    
    /**
    * Редактирование фото тура
    * 
    */
    function showEditPhoto()
    {
        $id = $this->getRequestParameter("id","int",0);
        try {
            if ( $id < 1 ) 
                throw new EUserMessageError("Не указан id страны");
            $inst = TourManager::getInstance()->getByPk($id);
            if (!TourManager::getInstance()->isRootInstance($inst))
                throw new EUserMessageError("Страна не найдена");
                
        } catch (EUserMessageError $exc) {
            return $exc->getMessage();
        }
                
        $this->__assign("tmpDir",fvSite::$fvConfig->get("path.upload.web_temp_image"));        
        $this->__assign("inst", $inst);    
        $this->__assign("wt", TourMediaManager::getInstance()->getListWeight() );
        
        return $this->__display("edit.photo.tpl");
    }
    function showImportTour($isCache=false)
    {
        if (!$isCache) {
            fvCache::getInstance()->setSerial("");
                $result = TourManager::getInstance()->doImportTour();
                $this->__assign("res",$result);
                $disp = $this->__display("importtour.tpl");
            fvCache::getInstance()->setSerial($disp);    
        } else {            
            return fvCache::getInstance()->getSerial();
        }
    }    
    function showCheckImport()
    {
        return $this->showImportTour(true);
    }
    
}