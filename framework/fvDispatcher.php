<?php

class fvDispatcher {
    private $app;
    private $_request;
    private $_responce;
    private $_params;
    private $_route;
    private $_redirectCount;
    private $_statusText;

    const MAX_REDIRECT = 100;

    public function __construct() {
        /* TODO : разкоментировать потом...
        $this->_request = new fvRequest();
        $this->_route = new fvRoute;
        $this->_responce = new fvResponce;
        */
    }
    
    function process() {       
        //$this->resolveApp($this->_request->getRequestParameter("__url"));
        $this->resolveApp('backend');
        echo "1) нужно разрешить app (backend/frontend)";
        /*
        * Получаем все контроллеры екшины и вызываем их после этого 
        * запихиваем получиную инфу в смарти и показываем вьюхи
        * 
        */ 
    }
    
    /*
    * Frontend Or Backend
    * в зависимости от того какой запрос - формируем нужные пути и создаем обьект Application
    * 
    */    
    function resolveApp($request) {       

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
