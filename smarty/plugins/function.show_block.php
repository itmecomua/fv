<?php
    function smarty_function_show_block($params, &$smarty)
    {
       $_params = array('resource_name' => fvSite::$fvConfig->get("path.smarty.blocks").$params['file']);
       require_once(SMARTY_CORE_DIR . 'core.get_php_resource.php');
       smarty_core_get_php_resource($_params, $smarty);
       $_smarty_resource_type = $_params['resource_type'];
       $_smarty_php_resource = $_params['php_resource'];       
       unset($params['file']);
       if(count($params))
       {
           foreach($params as $key=>$val)
           {
               $smarty->assign($key,$val);
           }
       }
       $smarty->display($_smarty_php_resource);      
    }
?>
