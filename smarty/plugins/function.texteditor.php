<?php
    function smarty_function_texteditor($params, &$smarty, $display = true)
    {
       $_params = array('resource_name' => fvSite::$fvConfig->get("path.smarty.blocks")."imperavi.editor.tpl");
       require_once(SMARTY_CORE_DIR . 'core.get_php_resource.php');
       smarty_core_get_php_resource($_params, $smarty);
       $_smarty_resource_type = $_params['resource_type'];
       $_smarty_php_resource = $_params['php_resource'];       
       
       
        $smarty->assign( 'id', $params["id"] );
        $smarty->assign( 'name', $params["name"] );
        $smarty->assign( 'text', $params["text"] );
        $smarty->assign( 'width', $params["width"] );
        $smarty->assign( 'height', $params["height"] );
       
       //$smarty->display( $_smarty_php_resource );      
       return $smarty->fetch( $_smarty_php_resource , null, null, $display);      
    }
?>