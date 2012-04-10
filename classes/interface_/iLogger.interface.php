<?php

interface iLogger {
    function getLogMessage($operation);
    
    function getLogName();
    
    function putToLog($operation);
}