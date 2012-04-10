<?php
    function smarty_function_show_media($params, &$smarty)
    {      
       
       $iMedia = $params['media'];
       switch ( intval( $iMedia->type_id ) )
       {
           case 1:
            $file = "image.tpl";
            $smarty->assign( 'iMedia', $iMedia );
            $smarty->assign( 'thumb', ( $params['thumb'] ) ? $params['thumb'] : false );
            break;
           case 2:
            $file = "video.tpl";
            $smarty->assign( 'width', ( $params['width'] ) ? $params['width'] : false );
            $smarty->assign( 'height', ( $params['height'] ) ? $params['height'] : false );
            $smarty->assign( 'iMedia', $iMedia );
            break;
           default:
            $file = "video.tpl";
            $smarty->assign( 'iMedia', $iMedia );
            $smarty->assign( 'width', ( $params['width'] ) ? $params['width'] : false );
            $smarty->assign( 'height', ( $params['height'] ) ? $params['height'] : false ); 
            break; 
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
