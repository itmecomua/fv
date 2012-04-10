<?php
define("FV_ROOT", realpath(dirname(__FILE__)) );
require( FV_ROOT . '/framework/interfaces/interfaces.php');
require_once( FV_ROOT . '/framework/fv.php' );
fv::process( require_once(FV_ROOT . '/config/main.php') );