<?php

abstract class fvModuleDictionary extends fvModule
{
    function showIndex() 
    {
        $search = $this->getRequest()->getRequestParameter('search');
        $order = $this->getRequest()->getRequestParameter('order');
        $page = $this->getRequestParameter('page');
        
        
        if ($search['_clear']=="1") {
            $search = $order = array();
            $page = 0;
        }
            
        $sessKey = $this->moduleName.__FUNCTION__.'filter';
                                    
        if (is_null($search) && is_null($order)) {
            $filter = fvSite::$fvSession->get($sessKey);
            $search = (array) $filter['search'];
            $order = (array) $filter['order'];
            $page = $page ? $page : (int) $filter['page'];            
        } else {
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

    function showEdit($instance=null) 
    {
        $id = $this->getRequestParameter();
        $inst = is_null($instance) || (is_array($instance)&&count($instance)==0)?$this->instance->getByPk($id,true):$instance;
                               
        $this->__assign('inst',$inst);
        $this->__assign('manager', $this->instance);
        return $this->__display('edit.tpl');
        
    }    
    
}
