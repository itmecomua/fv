<?php

class SitesModule extends fvModule {

    private $appName = 'frontend';
    private $appConfig = null;

    function __construct () 
    {
        $this->appConfig = new fvConfig(fvSite::$fvConfig->get("path.application.{$this->appName}.config"));
        $this->appConfig->Load("modules.yml");
        $this->appConfig->Load("template.yml");

        $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
        parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"), 
        fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"), 
        fvSite::$Layoult);
    }

    function showIndex() {
        if (!fvRequest::getInstance()->hasRequestParameter("page")) {
            if (fvSite::$fvSession->is_set(fvRequest::getInstance()->getRequestParameter("requestURL")."/page")) {
                fvRequest::getInstance()->putRequestParameter("page",
                    (int)fvSite::$fvSession->get(fvRequest::getInstance()->getRequestParameter("requestURL")."/page"));
            }
        } else {
            fvSite::$fvSession->set(fvRequest::getInstance()->getRequestParameter("requestURL")."/page",
                fvRequest::getInstance()->getRequestParameter("page"));
        }

        if (substr($requestURL = fvRequest::getInstance()->getRequestParameter("requestURL"), 0, 1) == "/") {
            $requestURL = substr($requestURL, 1);
        }

        $SiteManager = SiteManager::getInstance();
        //fvSite::$fvConfig->get("dir_web_root") . $requestURL .
        //$ManagerParams = ManagerParamManager::getInstance()->getAll();

        $this->__assign('Sites', $SiteManager->getAll());

        $request = fvRequest::getInstance();
        if (!$Site = SiteManager::getInstance()->getByPk($request->getRequestParameter('id'))) {
            $Site = new Site();
        }

		$apps = fvSite::$fvConfig->get('path.application');
		unset($apps['backend']);
		$apps = array_keys($apps);


        $this->__assign(array(
            'Site' => $Site,
            'apps' =>$apps
        ));

        return $this->__display('site_list.tpl');
    }
}
