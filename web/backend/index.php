<?php

$stime = microtime(true);

try{
    define("FV_APP", "backend");
    require_once("../../config.inc.php");
    
    fvDispatcher::getInstance()->process();
}
catch (Exception $e) {
    errorHandler($e);
}    
/*if (!fvRequest::getInstance()->isXmlHttpRequest())
    echo "<CENTER><TT>" . (sprintf("%.4f", (microtime(true) - $stime))) . "</TT></CENTER>";*/
?>