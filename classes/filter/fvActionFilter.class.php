<?php

class fvActionFilter implements iFilter {
    
    public function __construct() {
        
    }
    
    public function execute() {        
        fvSite::$fvSession->remove("login/redirectURL");
        
        $LayoultClass =  fvSite::$fvConfig->get("layoult");
        $layoult = fvSite::$Layoult = new $LayoultClass;
        $responce = fvResponce::getInstance();
        
        $actionName = fvRoute::getInstance()->getActionName();
        
        if (($action = fvDispatcher::getInstance()->getModule(fvRoute::getInstance()->getModuleName(), 'action')) === false) {
            fvDispatcher::getInstance()->redirect(fvSite::$fvConfig->get('page_404', 0, 404));
        }
        
        $result = $action->callAction($actionName);
        $module = fvDispatcher::getInstance()->getModule(fvRoute::getInstance()->getModuleName(), 'module');
        
        $responce->useLayoult(true);
        //var_dump($actionName);        
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
            $responce->setResponceBody($layoult->showPage());
        }
        return true;
    }
}
