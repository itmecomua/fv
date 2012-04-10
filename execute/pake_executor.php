<?php
	
	global $argv, $argc;	
	
	$currentDir = dirname(__FILE__) . "/";

	require_once(realpath(dirname(__FILE__) . "/../") .  "/config.inc.php");
	
	foreach (glob("{$currentDir}*Pake.class.php") as $pakeTask) {
		require_once($pakeTask);
	}
	
	if (empty($argv[1])) die("Pake Task not specified");
	
	$className = $argv[1] . "Pake";
	
	$pake = new $className();
	
	unset($argv[0]);
	unset($argv[1]);
	
	call_user_func(array($pake, "execute"), $argv);

?>