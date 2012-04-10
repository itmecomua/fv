<?php
class PriceOfDayModule extends fvModule
{
    function __construct () 
    {
        $this->moduleName = strtolower(substr(__CLASS__, 0, -6));

        parent::__construct(	
		                    /*  template */
							fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"), 
											
							/* compile  */
							fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"), 
											
							/* current_page */
							fvSite::$Layoult,
											
							/* fvRootManager $instance = null */
							null
							);
    }

	function showIndex()
    {
        $search     = $this->getRequestParameter('search', 'array', array());
        $sort       = $this->getRequestParameter('sort', 'array', array());
        $manager    = PriceOfDayManager::getInstance();
        $pager      = new fvPager( $manager );
        $where      = array();
        $order      = "";
        $List       = null;
              
        /* where */
        if ($search["name"]){
            $where[] = "name like '%" . addslashes($search["name"]) . "%'";
        }
        if( count($where) ){ 
            $where = implode(" AND ", $where );
        }
        
        /* order */
        if( !isset( $sort['dir'] ) ){
            $sort['dir'] = "desc";
        }
        if( !isset( $sort['field'] ) ){
            $sort['field'] = "id";
        }
        $order = $sort['field'] ." ".$sort['dir'];        
        
        
        $List = $pager->paginate($where, $order );
        
        $this->__assign("manager", $manager);
        $this->__assign("List", $List );
        $this->__assign("search", $search );
        $this->__assign("sort", $sort);        
        return $this->__display( 'index.tpl' );
                                
    }
                                                          
    function showEdit()
    {
        $manager    = PriceOfDayManager::getInstance();
        $id         = $this->getRequestParameter();
        $ex         = $manager->getByPk($id, true);

        $this->__assign("tmpDir",fvSite::$fvConfig->get("path.upload.web_temp_image"));
        $this->__assign("ex", $ex);
        $this->__assign("wt", range(-20, 20));
        $this->__assign("manager",$manager);
        return $this->__display( 'edit.tpl' );
    }
}