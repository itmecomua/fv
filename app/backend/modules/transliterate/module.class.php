<?php

class TransliterateModule extends fvModule
{
    var $moduleName;
    
    function __construct () 
    {
        $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
        parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"), 
        fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"), 
        fvSite::$Layoult);
    }

    function showIndex()
    {
        $this->__assign("langKeys", fvLang::getInstance()->getLangs());
        $this->__assign("langs", fvSite::$fvConfig->get('languages'));
        $this->__assign("keys", fvLang::getInstance()->getKeys());
        $this->__assign("tranliterate", fvLang::getInstance()->getTransliterate());
        return $this->__display("index.tpl");
    }
    
    function showGenerateurl()
    {
        $thisRequest = fvRequest::getInstance();
           $transliterator  = new Translit();
           $url = $transliterator->Transliterate($thisRequest->getRequestParameter("name"));
           $this->__assign('url',$url);
               
        return $this->__display( 'generateurl.tpl' );
    }
}

?>
