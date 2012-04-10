<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {show_module} plugin
 *
 * Type:     function<br>
 * Name:     show_module<br>
 * Purpose:  get module view result 
 * @param array
 * @param Smarty
 * @return string
 */

function smarty_function_show_module($params, &$smarty) {
	if (empty($params["module"]))
		$smarty->_trigger_fatal_error("[plugin] parameter 'module' cannot be empty");
	if (empty($params["view"]))
		$smarty->_trigger_fatal_error("[plugin] parameter 'view' cannot be empty");
		
    $module_name = $params["module"];
    $module_view = $params["view"];
    
    unset($params["module"]); unset($params["view"]);
                    
    $module = fvDispatcher::getInstance()->getModule($module_name, 'module');
    
    if ($module === false) {
        $smarty->_trigger_fatal_error("[plugin] module '$module_name' does not exists");
    }

    return $module->showModule($module_view, $params);
}
