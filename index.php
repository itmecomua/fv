<?php
define("FV_ROOT", realpath(dirname(__FILE__)) );
require_once( FV_ROOT . '/framework/fvSite.php' );
fvSite::start( require_once(FV_ROOT . '/config/main.php') );

/* TODO:
нада сделать инициализацию обьектов величинами из конфиг файла,
так как это сделано в Yii
*/