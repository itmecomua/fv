<?php

require_once(fvSite::$fvConfig->get("path.entity") . "fvroot.class.php");

abstract class fvUser extends fvUploaded {
	
	function __construct($fields, $tableName, $keyName = "id") {
        parent::__construct($fields, $tableName, $keyName);
	}
	
	abstract function getLogin();
	
	abstract function getFullName();
	
	abstract function check_acl ($acl_name, $action = 'index');
	
	abstract function isRoot();
}

?>
