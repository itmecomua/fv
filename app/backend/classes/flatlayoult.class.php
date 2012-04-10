<?php

class FlatLayoult extends fvLayoult {
    
    public function __construct() {
        fvSite::$Template->assign("Lang", fvLang::getInstance());        
        parent::__construct("main.tpl");
    }
    
    public function getPageContent() {
        return $this->getModuleResult();
    }
    
    function parseMeta ($meta_value) {
        return $meta_value;
    }
}
