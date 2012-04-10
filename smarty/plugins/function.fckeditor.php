<?php
    function smarty_function_fckeditor($params, &$smarty, $display = true)
    {              
       $editor = new fvFCKEditor($params["name"]);
       
       $editor->InstanceName = $params["id"];
       $editor->Height = $params["height"];
       $editor->Width = $params["width"];
       $editor->Class = $params["class"];
       $editor->Style = $params["style"];
                       
       $html = $editor->CreateHtml($params["text"]);
        
       return $html;
    }