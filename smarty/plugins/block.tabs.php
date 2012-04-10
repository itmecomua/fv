<?php

function smarty_block_tabs($params, $content, $smarty)
{
    $items = $params['items'];
    $rand = rand(0, 1000);
    $langNames = fvSite::$fvConfig->get('languages'); 
    $out = '';
    $out .= '<div id="tabs-'.$rand.'" >';
        $out .= '<ul>';
        foreach($items as $item)
        {
            $ln = $langNames[$item] ;
            $out .= '<li><a href="#tabs-item-'.$item.'">'. $ln['legend'] .'</a></li>';        
        }            
        $out .= '</ul>';
    $out .= $content;
    $out .= '</div>';
    $out .= '<script type="text/javascript">';
        $out .= 'jQuery(document).ready(function(){';
            $out .= 'jQuery("#tabs-'.$rand.'").tabs()';
         $out .='});';
    $out .= '</script>';    
    return $out;
}

?>