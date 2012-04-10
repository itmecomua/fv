<?php

class IndexModule extends fvModule {

    function __construct () 
    {
        $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
        parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"), 
        fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"), 
        fvSite::$Layoult);
    }
    function showIndex() {
        return $this->__display('index.tpl');
    }
    function showGenerateurl()
    {
        $thisRequest = fvRequest::getInstance();
           $transliterator  = new Translit();
           $url = $transliterator->Transliterate($thisRequest->getRequestParameter("name"));
               
        return $url;
    }

}

?>
