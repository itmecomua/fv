<?php

	define("FV_ROOT", realpath(dirname(__FILE__)) . "/");
	
	require_once(FV_ROOT . "includes/error_handler.inc.php");
	require_once(FV_ROOT . "classes/fvConfig.class.php");
		
	//preload needed classes
	require_once(FV_ROOT . "classes/fvMediaLib.class.php");
	
	$fvConfig = new fvConfig(FV_ROOT . "config/");
	$fvConfig->Load("app.yml");
	$fvConfig->Load("routes.yml");
	
	require_once($fvConfig->get("path.classes", "../classes/") . "fvSite.class.php");
	fvSite::setConfig($fvConfig);
	fvSite::initilize();
	
	
	
?>