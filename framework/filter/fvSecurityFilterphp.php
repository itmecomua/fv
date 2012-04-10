<?php

class fvSecurityFilter implements iFilter {
    
    public function __construct() {
        
    }
    
    public function execute() {
        $currentModule = fvRoute::getInstance()->getModuleName();
        $currentAction = fvRoute::getInstance()->getActionName();
        
        if ($currentModule == fvSite::$fvConfig->get("access.login_module")) return true;

        if (fvSite::$fvConfig->get("access.enable") || fvSite::$fvConfig->get("modules.{$currentModule}.access.enable")) {
            if ($LoggedUser = fvSite::$fvSession->getUser()) {
                if (fvSite::$fvConfig->get("access.login_acl") && $LoggedUser->check_acl(fvSite::$fvConfig->get("access.login_acl"))) {
                    if (!is_array(fvSite::$fvConfig->get("modules.{$currentModule}.access.acl")) || $LoggedUser->check_acl(fvSite::$fvConfig->get("modules.{$currentModule}.access.acl"), $currentAction)) {
                        return true;
                    } else {
                        fvDispatcher::getInstance()->redirect(fvSite::$fvConfig->get("access.deny_page"));
                    }
                } else {
                    fvDispatcher::getInstance()->redirect(fvSite::$fvConfig->get("access.deny_page"));
                }
            }
            fvSite::$fvSession->set("login/redirectURL", fvRequest::getInstance()->getRequestParameter('requestURL'));
            fvDispatcher::getInstance()->forward(fvSite::$fvConfig->get("access.login_page"));
            return false;
        }
    }
}
