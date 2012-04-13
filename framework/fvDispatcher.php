<?php

class fvDispatcher {
    private $_request;
    private $_route;
    private $_responce;    
    
    private $_params;    
    private $_redirectCount;
    

    private $_statusText;

    const MAX_REDIRECT = 100;

    public function __construct() {
        $this->_request     = new fvRequest();
        /*
        $this->_route       = fvRoute::getInstance();
        $this->_responce    = fvResponce::getInstance();
        $this->_config      = fvSite::getFvConfig();
        */
    }

    function process() 
    {
        $this->app = new fvApplication( $this->resolveApp( $this->_request->getRequestApp() ) );
        
    }
    
    private function resolveApp($app)
    {
        if( !(in_array( $app , fvSite::getConfig()->get('applist') )) )
        {
            $app = fvSite::getConfig()->get('frontendapp');
        }
        return $app;
    }
    
    
    function getModule($module, $type) 
    {
        
        if (!class_exists($class = fvSite::$fvConfig->get("modules.{$module}.{$type}_class"))) 
        {
            if (file_exists(fvSite::$fvConfig->get("modules.{$module}.path") . "{$type}.class.php")) 
            {
                require_once(fvSite::$fvConfig->get("modules.{$module}.path") . "{$type}.class.php");
            }
            else {
                require_once(fvSite::$fvConfig->get("modules.staticpages.path") . "{$type}.class.php");
                $class = fvSite::$fvConfig->get("modules.staticpages.{$type}_class");
            }
        }
        return new $class;
    }

    function forward($url) {
        //echo self::MAX_REDIRECT;                                                  ;
        if (++$this->_redirectCount > self::MAX_REDIRECT){
            throw new EDispatcherExeception("Max redirect count reached");}
         $this->_route->process($url); //var_dump($this->_route->process($url));
         
        if (fvFilterChain::getInstance()->execute() !== false) {
            $this->_responce->sendHeaders();
            $this->_responce->sendResponceBody();
        }
       --$this->_redirectCount;
    }


    function redirect($url, $delay = 0, $status = 302) {
        $this->_responce = fvResponce::getInstance();
        $this->_responce->clearHeaders();
        $this->_responce->setStatus($status);
        $this->_responce->setHeader("Location", $url);
        $this->_responce->setResponceBody('<html><head><meta http-equiv="refresh" content="%d;url=%s"/></head></html>', $delay, htmlentities($url, ENT_QUOTES, fvSite::$fvConfig->get('charset')));

        $this->_responce->sendHeaders();
        $this->_responce->sendResponceBody();
        die();
    }
}
