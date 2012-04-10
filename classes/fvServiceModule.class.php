<?php

class fvServiceModule extends fvModule {
    protected $moduleName = '';

    function __construct($template, $compile, $current_page, $moduleName) {
        $this->moduleName = $moduleName;

        parent::__construct($template, $compile, $current_page);


    }

    function showIndex() {
        ini_set("soap.wsdl_cache_enabled", "0");
        $server = new SoapServer(fvSite::$fvConfig->get('path.wsdl'));
        $server->setClass($this->moduleName . 'Action');
        $server->handle();
    }
}