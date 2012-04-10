<?php
class NewsModule extends fvModule
{
    var $moduleName;
    
    function __construct () 
    {
        $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
        parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"), 
        fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"), 
        fvSite::$Layoult);
    }

	function showIndex()
    {
       $request = fvRequest::getInstance();
       
       $sort['field'] = $request->getRequestParameter('field');
       $sort['dir'] = $request->getRequestParameter('direct');
       
       $search = $request->getRequestParameter('search');
       
       $query = NULL;
       if($search['_clear'] == 1) 
       {
            $search = array(); 
            $search['_clear'] = 1;
       }
       
       if($search['name']) 
            $query = 'name like "%'.str_replace('"',"'",$search['name']).'%"';

       if( !$sort['field'] )
       {
           $sort['field'] = "weight";
           $sort['dir'] = "asc";
       }              
       
       
       $pager = new fvPager( NewsManager::getInstance() );
       $this->__assign('List', $pager->paginate($query,$sort['field']." ".$sort['dir']));  
       

       $this->__assign("module",$this->moduleName);
       $this->__assign("sort",$sort);
      
       $this->__assign("search",count($search));
       return $this->__display( 'index.tpl' );
                                
    }
                                                          
    function showEdit()
    {
        $request = fvRequest::getInstance();
        if (!$ex = NewsManager::getInstance()->getByPk($request->getRequestParameter('id')))
        {
            $ex = new News();                     
        }
        
        $this->__assign("tmpDir",fvSite::$fvConfig->get("path.upload.web_temp_image"));
        $this->__assign('ex',$ex); 
        $this->__assign('metaManager',MetaManager::getInstance()); 
        $this->__assign("module",$this->moduleName);
        $this->__assign("weights",range(0,999));
        return $this->__display( 'edit.tpl' );
    }
}