<?php

class fvSite {
    public static $fvConfig;
    public static $DB;
    public static $fvSession;
    public static $Template;
    public static $currentModules;
    public static $Layoult;
    public static $fvRequest;
    public static $fvParams;

    public static function initilize () {

        //include core classes. Exceptions.
        if (!(fvSite::$fvConfig instanceof fvConfig)) user_error("Can't find loaded config class", E_USER_ERROR);

        $exceptionsDir = fvSite::$fvConfig->get("path.exceptions", "../classes/exceptions/");
        
        foreach (glob("{$exceptionsDir}*.class.php") as $exceptions) 
        {
            require_once($exceptions);
        }

        //ititilize DB core

        require_once(fvSite::$fvConfig->get("path.classes", "../classes/") . "DB.php");

        if (!$dsn = fvSite::$fvConfig->get("database.dsn")) {
	         $dsn = fvSite::$fvConfig->get("database.driver", "mysql") . "://" .
	                fvSite::$fvConfig->get("database.user", "root") . ":" .
	                fvSite::$fvConfig->get("database.pass", "") . "@" .
	                fvSite::$fvConfig->get("database.host", "localhost") . "/" .
	                fvSite::$fvConfig->get("database.name", "fv");
        }

        PEAR::setErrorHandling(PEAR_ERROR_CALLBACK, "errorHandler");

        $DB = DB::connect($dsn);
        $DB->setFetchMode(DB_FETCHMODE_ASSOC);
        $DB->query("set names utf8");

        fvSite::setDB($DB);

        //try to load schema yml
        fvSite::$fvConfig->Load(fvSite::$fvConfig->get("path.config") . "schema.yml", true);
        if (file_exists(fvSite::$fvConfig->get("path.config") . "acl.yml"))
               fvSite::$fvConfig->Load(fvSite::$fvConfig->get("path.config") . "acl.yml", true);


        //initilize core classes
        $entityDir = fvSite::$fvConfig->get("path.entity", "../classes/entity/");
        $classDir = fvSite::$fvConfig->get("path.classes", "../classes/");
        $filterDir = fvSite::$fvConfig->get("path.filters", "../classes/filter");
        $interfaceDir = fvSite::$fvConfig->get("path.interfaces", "../classes/interface");        

        foreach (glob("{$interfaceDir}i*.interface.php") as $entity) {
            require_once($entity);
        }

        foreach (glob("{$classDir}*.class.php") as $entity) {

            require_once($entity);
        }

        foreach (glob("{$entityDir}fv*.class.php") as $entity) {
            //echo "$entity<br/>";
            require_once($entity);
        }
          // echo "2";
        foreach (glob("{$filterDir}fv*.class.php") as $entity) {
            require_once($entity);
        }


        foreach (glob("{$entityDir}*_manager.class.php") as $entity) {
            if (strpos("root", $entity) === false) {
                require_once($entity);
            }
        }

        self::setParams(fvParams::getInstance());

        //try to initilize template engine, if we have an application definded

        require_once(fvSite::$fvConfig->get("path.classes", "../classes/") . "fvSession.class.php");
        $fvSession = new fvSession(fvSite::$fvConfig->get("session.sess_name", "fv_session"),
                                   fvSite::$fvConfig->get("session.life_time", 3600),
                                   fvSite::$fvConfig->get("session.table", "fv_session"));

        $fvSession->start();
        fvSite::setSession($fvSession);

        fvSite::setRequest(fvRequest::getInstance());

        if (defined("FV_APP")) {

            //Load main application config
            fvSite::$fvConfig->Load(fvSite::$fvConfig->get("path.application." . FV_APP . ".config") . "app.yml", true);

            require_once(fvSite::$fvConfig->get("path.smarty.class_path") . "smarty.class.php");

            $smarty = new Smarty();
            $smarty->template_dir = fvSite::$fvConfig->get("path.smarty.template");
            $smarty->compile_dir = fvSite::$fvConfig->get("path.smarty.compile");
            fvSite::setTemplate($smarty);

            fvSite::$Template->assign("fvConfig", fvSite::$fvConfig);
            fvSite::$Template->assign("fvUser", fvSite::$fvSession->getUser());

            //Load routes for application
            fvSite::$fvConfig->Load(fvSite::$fvConfig->get("path.application." . FV_APP . ".config") . "routes.yml", true);

            //Load modules config
            fvSite::$fvConfig->Load(fvSite::$fvConfig->get("path.application." . FV_APP . ".config") . "modules.yml", true);
            fvSite::$currentModules = fvSite::$fvConfig->get("modules");

            //Load app classes

            if (file_exists(fvSite::$fvConfig->get("path.application." . FV_APP . ".config") . "acl.yml"))
               fvSite::$fvConfig->Load(fvSite::$fvConfig->get("path.application." . FV_APP . ".config") . "acl.yml", true);
               
            //Load app classes
            //Загрузка конфига с переводами и ключами 
            if (file_exists(fvSite::$fvConfig->get("path.config") . "languages.txt"))            
                fvSite::$fvConfig->appendToConfig("transliterate", unserialize( file_get_contents(fvSite::$fvConfig->get("path.config"). "languages.txt")) );                


            $appClassesDir = fvSite::$fvConfig->get("path.application." . FV_APP . ".classes");

            foreach (glob("{$appClassesDir}*.class.php") as $entity) {
                require_once($entity);
            }
        }

    }

    public static function setConfig(fvConfig $Config) {
        fvSite::$fvConfig = $Config;
    }

    /**
    * @return DB_mysql
    */
    public static function setDB($DB) {
        fvSite::$DB = $DB;
    }

    public static function setSession(fvSession $fvSession) {
        fvSite::$fvSession = $fvSession;
    }

    public static function setTemplate(Smarty $Template) {
        fvSite::$Template = $Template;
    }

    public static function setRequest (fvRequest $fvRequest) {
        self::$fvRequest = $fvRequest;
    }

	public static function setParams (fvParams $fvParams) {
		self::$fvParams = $fvParams;
	}
}

?>
