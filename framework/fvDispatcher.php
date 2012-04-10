<?php

class fvDispatcher {
    private $app;
    
    
    protected $_request;
    protected $_responce;
    protected $_params;
    protected $_route;
    protected $_redirectCount;

    protected $_statusText;

    const MAX_REDIRECT = 100;

    public function __construct() {
        $this->_request = fvRequest::getInstance();
        $this->_route = fvRoute::getInstance();
        $this->_responce = fvResponce::getInstance();
    }
    function process() {       
        $this->resolveApp($this->_request->getRequestParameter("__url"));
        /*
        * Получаем все контроллеры екшины и вызываем их после этого 
        * запихиваем получиную инфу в смарти и показываем вьюхи
        * 
        */ 
    }
    function resolveApp($request) {       
        /*
        * Frontend Or Backend
        * 
        * в зависимости от того какой запрос - формируем нужные пути и создаем обьект Application
        */
        $this->app = new fvApplication();
    }

     function getModule($module, $type) 
    {
               
        if (!class_exists($class = fv::getFvConfig()->get("modules.{$module}.{$type}_class"))) 
        {
            if (file_exists(fv::getFvConfig()->get("modules.{$module}.path") . "{$type}.class.php")) 
            {
                require_once(fv::getFvConfig()->get("modules.{$module}.path") . "{$type}.class.php");
            }
            else {
            	require_once(fv::getFvConfig()->get("modules.staticpages.path") . "{$type}.class.php");
            	$class = fv::getFvConfig()->get("modules.staticpages.{$type}_class");
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
        $this->_responce->setResponceBody('<html><head><meta http-equiv="refresh" content="%d;url=%s"/></head></html>', $delay, htmlentities($url, ENT_QUOTES, fv::getFvConfig()->get('charset')));

        $this->_responce->sendHeaders();
        $this->_responce->sendResponceBody();
        die();
    }
}
