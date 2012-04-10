<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty urlto modifier plugin
 *
 * Type:     modifier<br>
 * Name:     urlto<br>
 * Date:     Feb 24, 2003
 * Input:    url to check
 * Example:  {$var|urlto}
 * @param string
 * @return string
 */
function smarty_modifier_urlto($url)
{
    if ((substr($url, 0, 1) == "@") || (substr($url, 0, 4) != "http")) {
        return fvSite::$fvConfig->get("path.application." . $application . ".web_root") . substr($url, 1);
    }
    else {
        return $url;
    }
}

/* vim: set expandtab: */

?>

