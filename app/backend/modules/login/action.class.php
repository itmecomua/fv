<?php

class LoginAction extends fvAction {
    
    function __construct () {
        parent::__construct(fvSite::$Layoult);
    }
    
    function executeIndex() {
        if (!fvRequest::getInstance()->isXmlHttpRequest()) {
            $this->current_page->setTitle('Login Page');
            $this->current_page->setTemplate('loginLayoult.tpl');
            return self::$FV_OK;
        } else {
            return self::$FV_AJAX_CALL;
        }
    }
    
    function executeDeny() {
        $this->current_page->setTitle('Deny Page');
        $this->current_page->setTemplate('loginLayoult.tpl');
        return self::$FV_OK;
    }
    
    function executeLogin() 
    {        
        $className = fvSite::$fvConfig->get('access.user_class');
        
        $UserManager = call_user_func(array("{$className}Manager", 'getInstance'));
        $request = fvRequest::getInstance();
        
        $login = $request->getRequestParameter("login");
        $password = md5($request->getRequestParameter('password'));
        $site = fvSite::$fvConfig->get('server_name');
        $ip = $_SERVER['SERVER_ADDR'];
        
        
        $LoggedUser = $UserManager->Login($login, $password, $site, $ip);
        
        if ($LoggedUser !== false) 
        {
            fvSite::$fvSession->setUser($LoggedUser);
            fvResponce::getInstance()->setHeader('loginsuccess', '1');
            if (fvSite::$fvSession->is_set("login/redirectURL"))
                $this->redirect("@#" . fvSite::$fvConfig->get('dir_web_root') . fvSite::$fvSession->get("login/redirectURL"));
            else $this->redirect("@");
        } else {
            $this->setFlash("Ошибка при аутентификации пользователя. Пара логин/пароль не найдена", self::$FLASH_ERROR);
            //$this->redirect(fvSite::$fvConfig->get('access.login_page'));
        }
        
        if (!fvRequest::getInstance()->isXmlHttpRequest()) {
            return self::$FV_OK;
        } else {
            return self::$FV_AJAX_CALL;
        }
        
    }

    function executeLogout() {
        fvSite::$fvSession->setUser(false);
        $this->redirect("");
    }
    
    function executeLoginform() {
        if (!fvRequest::getInstance()->isXmlHttpRequest()) {
            $this->redirect404();
        } else {
            return self::$FV_AJAX_CALL;
        } 
    }
}

?>
