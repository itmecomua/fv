<?php
/*
*  1) Установака константы FV_ROOT - корень файловой стистемы сайта 
*  2) Получение массива конфига
*  3) Запуск fvSite::start - инициализация 
*/
define("FV_ROOT", realpath(dirname(dirname(__FILE__))) );
require_once( FV_ROOT . '/framework/fvSite.php' );
fvSite::start( require_once(FV_ROOT . '/config/main.php') );