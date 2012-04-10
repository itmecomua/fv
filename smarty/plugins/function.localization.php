<?php
require_once fvSite::$Template->_get_plugin_filepath('function', 'texteditor');
require_once fvSite::$Template->_get_plugin_filepath('function', 'fckeditor');
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
/**
 * Smarty {localization} function plugin
 *
 * Type:     function<br>
 * Name:     localization<br>
 * Input:<br>
 *           - object       (required) - object model
 *           - langs       (required) - array langs
 *           - name       (required) - string - value for send name
 * @author Nesterenko Nikita
 * @since 2011/04/20
 * @param array $params, object $smarty
 * @returns lang-compiled html data
 * Назначение: преобразует объект в локализированные данные для редактирования
 */ 
function smarty_function_localization($params, &$smarty)
{
    $object = $params['object'];
    $langs = $params['langs'];
    $name = $params['name'];
    if(!$object) if(!$box) $smarty->_trigger_fatal_error("Attribute 'object' doesn't exist");
    if(!is_array($langs)) if(!$box) $smarty->_trigger_fatal_error("Attribute 'langs' not array");
    if(!$name) if(!$name) $smarty->_trigger_fatal_error("Attribute 'name' doesn't exist");
    
    $fieldList = $object->getLangFields(true);
    
    $out = '';
    $langNames = fvSite::$fvConfig->get('languages');
    foreach($langs as $lang)
    {
        $out .= '<div id="tabs-item-'.$lang.'">';
        foreach($fieldList as $nameField => $params)
        {
            $langField = $nameField."_".$lang;
            if(!isset($params['field'])) $params['field'] = "input";
            if($params['field'] == 'input')
            {                    
                $out .= '<div class="field">';            
                    $out .= '<label>'.fvLang::getInstance()->$nameField.'</label>';
                    $out .= createInputElement($langField, $name, $object->$langField, $params);
                $out .= '</div>';
            }
            if($params['field'] == 'textarea')
            {
                $out .= '<div class="field">';
                    $out .= '<label>'.fvLang::getInstance()->$nameField.'</label>';
                    $out .= createTextAreaElement($langField, $name, $object->$langField, $params);
                $out .= '</div>';
            }
            if($params['field'] == 'redactor')
            {
                $out .= '<div class="field">';
                    $out .= '<label>'.fvLang::getInstance()->$nameField.'</label><div>';
                    $out .= createRedactorElement($langField, $name, $object->$langField, $params, $smarty);
                $out .= '</div></div>';
            }
        }
        $out .= '</div>';
    }
    return $out;
}


function createInputElement($nameField, $nameInput, $value, $params)
{
    $nameInput = preg_match("/\[\]/", $nameInput) ? str_replace("[]", "[".$nameField."]", $nameInput) : $nameField;
    $class = $params['validate'] ? "notEmpty" : "";
    return '<input name="'.$nameInput.'" type="text" maxlength="255" class="'.$class.'" value="'.$value.'" id="'.$nameField.'" />';
}
function createTextAreaElement($nameField, $nameInput, $value, $params)
{
    $nameInput = preg_match("/\[\]/", $nameInput) ? str_replace("[]", "[".$nameField."]", $nameInput) : $nameField;    
    $class = $params['validate'] ? "notEmpty" : "";
    return '<textarea name="'.$nameInput.'" class="'.$class.'" id="'.$nameField.'" >'.$value.'</textarea>';
}
function createRedactorElement($nameField, $nameInput, $value, $params, $smarty)
{   
    
    $nameInput = preg_match("/\[\]/", $nameInput) ? str_replace("[]", "[".$nameField."]", $nameInput) : $nameField;
    $smartyParams['name'] = $nameInput;
    $smartyParams['width'] = "700px";
    $smartyParams['height'] = "250px";
    $smartyParams['text'] = $value;
    $smartyParams['id'] = $nameField;
    return smarty_function_fckeditor($smartyParams, $smarty, false);
}

?>
