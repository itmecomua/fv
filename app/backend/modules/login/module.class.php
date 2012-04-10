<?php

class LoginModule extends fvModule {

    function __construct () 
    {
        $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
        parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"), 
        fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"), 
        fvSite::$Layoult);
    }

    function showIndex() 
    {
        return $this->__display('login.tpl');
    }

    function showDeny() 
    {
        return $this->__display("deny.tpl");
    }
    
    function showLoginform() 
    {
        return $this->__display('login_form.tpl');
    }
}

?>
