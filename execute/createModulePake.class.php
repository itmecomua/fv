<?php

class createModulePake extends fvPake {
	
	function execute($params) {
		($application = $params[2]) or die("Can't get application name");
		($module = $params[3]) or die("Can't get application name");
		
		define("FV_APP", $application);
		
        fvSite::$fvConfig->Load(fvSite::$fvConfig->get("path.application." . FV_APP . ".config") . "app.yml", true);

        var_dump(fvSite::$fvConfig->get("path.modules"));
        
        $modulesymlContent = "\n  {$module}: 
    path: %path.modules%{$module}/
    menu_path: ~
    action_class: " . ucfirst($module) . "Action
    module_class: " . ucfirst($module) . "Module
    name: ~
    smarty:
      template: %modules.{$module}.path%template/
      compile: %modules.{$module}.smarty.template%template_c/"; 
        
        
      	file_put_contents(fvSite::$fvConfig->get("path.application." . FV_APP . ".config") . "modules.yml", file_get_contents(fvSite::$fvConfig->get("path.application." . FV_APP . ".config") . "modules.yml") . $modulesymlContent);
      	
      	
		
	}
}

?>
