<?php
/*
*  1) Разбор URL (Request ) (независимо от Application )
*  2) Создание  "нужного" Application, все компоненты будут получены именно от Application
*  3) Получение модуля (от Application )
*  4) Получения Акшина (от Application )
*  5) Прохождение фильтров  ( Filters ) (от Application )
*  6) Шаблон ( Layout ) (от Application )
*  7) Страница  ( Page ) (от Application )
*  8) Установка ответа  ( Response ) (независимо от Application )
*  9) Посылка ответа пользователю ( Response ) (независимо от Application )
*/

class fvDispatcher {
    private $curentAppName;
    private $_app;


    private $_request;
    private $_application;
    private $_filter;
    private $_layout;
    private $_page;    
    private $_responce;
    


    private static $_Template;
    private static $_currentModules;
    private static $_Layoult;
    private static $_fvRequest;
    private static $_fvParams;    


    private $_params;    
    private $_redirectCount;
    private $_statusText;

    const MAX_REDIRECT = 100;

    public function __construct() {
        $this->_request     = new fvRequest();
        $this->_route       = new fvRoute();
        /*
        $this->_responce    = fvResponce::getInstance();
        $this->_config      = fvSite::getFvConfig();
        */
    }

    function process() 
    {
        $this->curentAppName = $this->resolveAppName($this->_request->getRequestUrlparts());
        $this->_app = new fvApplication($this->curentAppName);
        
        /*
        if (++$this->_redirectCount > self::MAX_REDIRECT){
            throw new EDispatcherExeception("Max redirect count reached");}
         $this->_route->process($url);
        if (fvFilterChain::getInstance()->execute() !== false) {
            $this->_responce->sendHeaders();
            $this->_responce->sendResponceBody();
        }
       --$this->_redirectCount;
       */
       
    }
    
/* 
* узнаем какой аппликейшн запрашивает пользователеь 
* 
*/
    private function resolveAppName( $requestUrlParts ){                      
        $appList    = fvSite::getConfig()->get('applist');
        $defaultApp = fvSite::getConfig()->get('defaultapp');        
        $resultApp  = isset($requestUrlParts[0])?$requestUrlParts[0]:"";
       
        if( $resultApp === "" || (!in_array( $resultApp , $appList ) ) ){
            $resultApp = $defaultApp;
        }

        return $resultApp;
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
    
    
    public function execute() {        
        /*
        fvSite::$fvSession->remove("login/redirectURL");
        $LayoultClass =  fvSite::$fvConfig->get("layoult");
        $layoult = fvSite::$Layoult = new $LayoultClass;
        $responce = fvResponce::getInstance();
        */
        $actionName = fvRoute::getInstance()->getActionName();
        
        if (($action = fvDispatcher::getInstance()->getModule(fvRoute::getInstance()->getModuleName(), 'action')) === false) {
            fvDispatcher::getInstance()->redirect(fvSite::$fvConfig->get('page_404', 0, 404));
        }
        
        $result = $action->callAction($actionName);
        $module = fvDispatcher::getInstance()->getModule(fvRoute::getInstance()->getModuleName(), 'module');
        
        $responce->useLayoult(true);
      
        switch ($result) 
        {
            case fvAction::$FV_OK:
                if ($module === false) {
                    fvDispatcher::getInstance()->redirect(fvSite::$fvConfig->get('page_404', 0, 404));
                }
                
                $layoult->setModuleResult($module->showModule($actionName));
                break;
            case fvAction::$FV_NO_ACTION:
                if (($module === false) || (($moduleResult = $module->showModule($actionName)) == fvModule::$FV_NO_MODULE)) {
                    fvDispatcher::getInstance()->redirect(fvSite::$fvConfig->get('page_404', 0, 404));
                }
                $layoult->setModuleResult($moduleResult);
                break;
            case fvAction::$FV_ERROR:
                fvDispatcher::getInstance()->redirect(fvSite::$fvConfig->get('error_page', 0, 404));
                break;
            case fvAction::$FV_NO_LAYOULT_MODULE:
                
                
                break;
            case fvAction::$FV_NO_LAYOULT:
            case fvAction::$FV_AJAX_CALL:
                    $responce->useLayoult(false);
                    if (($module !== false) && (($moduleResult = $module->showModule($actionName)) != fvModule::$FV_NO_MODULE)) {
                        $responce->setResponceBody($moduleResult);
                    }
                break;
            default:
                return false;
                break;
        }
        if ($responce->useLayoult()) {
            
            $tmp = $layoult->showPage();
            
            
            $spohere = 1;
            
            
//            $responce->setResponceBody();
        }
        return true;
    }    
}
