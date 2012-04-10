<?php
  function smarty_function_show_breadcrumps($params, &$smarty)
  {
    $iObject = $params['iObject'];
    
    if ( $iObject instanceof News )
    {
        $smarty->assign( 'iNews', $iObject );
        $file = "breadcrumps.news.tpl";
    }
    
    if ( $iObject instanceof Event )
    {
        $smarty->assign( 'iEvent', $iObject );
        $file = "breadcrumps.event.tpl";
    }  
    
    if ( $iObject instanceof Album )
    {
        $smarty->assign( 'iAlbum', $iObject );
        $file = "breadcrumps.album.tpl";
    }
    
    if ( $iObject instanceof Category )
    {
        $smarty->assign( 'iCategory', $iObject );
        $file = "breadcrumps.category.tpl";
    } 
    
       $_params = array('resource_name' => fvSite::$fvConfig->get("path.smarty.blocks").$file );
       require_once(SMARTY_CORE_DIR . 'core.get_php_resource.php');
       smarty_core_get_php_resource($_params, $smarty);
       $_smarty_resource_type = $_params['resource_type'];
       $_smarty_php_resource = $_params['php_resource'];       
       unset($params['file']);
       
       
       $smarty->display($_smarty_php_resource);   
        
  }
?>
