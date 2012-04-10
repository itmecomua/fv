<?php

class fvRoute {
    
    protected $_routeName;
    protected $_moduleName;
    protected $_actionName;
    protected $_routes;
    protected $_requestURL;
    
    protected function __construct() {
        $this->_routes = fvSite::getFvConfig()->get('routes');
    }
    
    public static function getInstance() {
        static $instance;
        if (empty($instance)) {
            $instance = new self();
        }
        return $instance;
    }
    
    public function processURL($currentURL) 
    {                   
        if (substr($currentURL, 0, 1) !== '/') $currentURL = "/" . $currentURL;
        $matches = array();
        $r = fvRequest::getInstance();
        
        
        foreach ($this->_routes as $routeName => $route) {
            $urlArray = explode("/", $route['url']);
            $url = '';
            $paramsArray = array();
            $i = 0;
            
            foreach ($urlArray as $urlPath) {
                if (strlen(trim($urlPath)) == 0) continue;
                if (strpos($urlPath, ":") !== false) {
                    if (isset($route['params'][substr($urlPath, 1)])) {
                        $url .= "\/?(" . $route['params'][substr($urlPath, 1)] . ")";
                    } else {
                        $url .= "\/?([^\/]*)";
                    }
                    $paramsArray[substr($urlPath, 1)] = ++$i;
                } else {
                    $url .= "\/?" . $urlPath;
                }
            }
            if ($url == '') $url = "\/";
            
            if (preg_match("/^".$url."/i", $currentURL, $matches)) {
                $this->_routeName = $routeName;
                if (!($this->_moduleName = ($route['module'])?$route['module']:$matches[$paramsArray['module']])) {
                    $this->_moduleName = "index";
                }
                if (!($this->_actionName = ($route['action'])?$route['action']:$matches[$paramsArray['action']])) {
                    $this->_actionName = "index";
                }
                
                foreach ($paramsArray as $requestKey => $matchId) {
                    $r->putRequestParameter($requestKey, $matches[$matchId]);
                }
                break;
            }
        }
        
        $this->_requestURL = $currentURL;
        
        $r->putRequestParameter('module', $this->_moduleName);
        $r->putRequestParameter('action', $this->_actionName);
        $r->putRequestParameter('requestURL', $currentURL);
        return array('module' => $this->_moduleName, 'action' => $this->_actionName);
    }
    
    public function processRoute($route) {
        $currentRoute = $this->_routes[$route];
        
        $this->_routeName = $currentRoute;
        $this->_moduleName = ($route['module'])?$route['module']:'index';
        $this->_actionName = ($route['action'])?$route['action']:"index";
        
        return array('module' => $this->_moduleName, 'action' => $this->_actionName);
    }
    
    
    public function process($url) {
        if (substr($url, 0, 1) == "@") {
            return $this->processRoute(substr($url, 1));
        } else {
            return $this->processURL($url);
        }
    }
    
    public function getRouteName() {
        return $this->_routeName;
    }
    
    public function getModuleName() {
        return $this->_moduleName;
    }
    
    public function getActionName() {
        return $this->_actionName;
    }
    
    public function getRequestURL () {
        return $this->_requestURL;
    }
}
