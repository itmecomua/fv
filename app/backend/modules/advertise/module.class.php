<?php

class AdvertiseModule extends fvModule
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
        $search = $this->getRequestParameter('search', 'array', array());
        $sort = $this->getRequestParameter('sort', 'array', array());
        
        
        $sort['dir'] = $sort['dir'] ? $sort['dir'] : "desc";
        $sort['field'] = $sort['field'] ? $sort['field'] : "id";
        
        $order = $sort['field'] ." ".$sort['dir'];
        if(count($where)) $where = implode(" AND ", $where );
        $pager = new fvPager( AdvertiseManager::getInstance() );
        $where = array();
        
        if ($search["type_id"]) {
            $where[] = "type_id=".intval($search["type_id"]);
        }
        $where = count($where) ? implode(" and ",$where) : null;
        $List = $pager->paginate($where, $order );
        $this->__assign("manager",AdvertiseManager::getInstance());
        $this->__assign("List", $List );
        $this->__assign("search", $search );
        $this->__assign("sort", $sort);        
        return $this->__display( 'index.tpl' );
                                
    }
                                                          
    function showEdit()
    {
        $id = $this->getRequestParameter();
        $ex = AdvertiseManager::getInstance()->getByPk($id, true);
        
        
        $this->__assign("tmpDir",fvSite::$fvConfig->get("path.upload.web_temp_image"));
        
        $this->__assign("ex", $ex);
        $this->__assign("wt", range(-20, 20));
        $this->__assign("manager",AdvertiseManager::getInstance());
        return $this->__display( 'edit.tpl' );
    }
}

?>
