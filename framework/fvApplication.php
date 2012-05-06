<?php
class fvApplication extends fvUnit
{  
    private $_request;
    private $_urlManager;
    private $_db;
    
    
    
    
    
    private $_defaultController;   
    private $controllerMap;
    
    
    
    /* список имен модулей которые подключены к Аппликейшену */
    private $_modulesIncluded;
    
    /* "дополнительная память" для ускоренного доступа к обьектам модулей */
    private $_modules;

        
    public function __construct( $appName )
    {
        fvSite::getConfig()->setPathByAlias('currentApp' , fvSite::getConfig()->getPathByAlias('app') . $appName ) ;
        fvSite::getConfig()->Load( fvSite::getConfig()->getPathByAlias('currentApp.config') . "app.php" );
        $this->setUrlManager();
        $this->setRequest();
        $this->setDb();
    }

    private function setUrlManager()
    {
        fvSite::Import( 'currentApp.classes.urlManager' );
        $this->_urlManager = fvSite::getSingleton('urlManager');
    }
    
    private function getUrlManager()
    {
        return $this->_urlManager;
    }
    
    private function setRequest()
    {
        $this->_request = fvSite::getSingleton('fvRequest');
    }
    
    private function getRequest()
    {
        return $this->_request;
    }

    private function setDb()
    {
        $this->_db = fvSite::getSingleton('fvDb');
    }
    
    private function getDb()
    {
        return $this->_db;
    }

    
    public function setModulesIncluded( array $modulesIncluded )
    {
        $this->_modulesIncluded = $modulesIncluded;
    }  

    public function getModulesIncluded()
    {
        return $this->_modulesIncluded;
    }      

    public function setModulesConfigs( array $modulesConfigs )
    {
        $this->_modulesConfigs = $modulesConfigs;
    }  

    public function getModulesConfigs()
    {
        return $this->_modulesConfigs;
    }      

    public function getAllModules()
    {
        return $this->_modules;
    }      
    
    public function getModule( $id )
    {
        if( isset( $this->_modules[$id] ) ) 
        {
            return $this->_modules[$id];
        }            
        else if( isset( $this->_modulesIncluded[$id] ) )
        {
            $currentAppModulesDir   = fvSite::getConfig()->getPathByAlias( 'currentApp.modules');
            $currentModuleDir       = $id;
            $currentModuleFileName  = fvSite::getConfig()->getSeting('ModuleMainFileName').fvSite::getConfig()->getExecuteFileExtension();
            $currentModuleClassName = ucfirst( $id ).ucfirst( fvSite::getConfig()->getSeting( 'ModuleMainFileName' ) );
            fvSite::Import( $currentAppModulesDir . $currentModuleDir . DIRECTORY_SEPARATOR . $currentModuleFileName , $currentModuleClassName );
            $this->_modules[$id] = fvSite::getSingleton( $currentModuleClassName );
            $this->_modules[$id]->setBasePath( $currentAppModulesDir . $currentModuleDir );
            return $this->_modules[$id];
        }
        else
        {
            return false;
        }
    }    
    
    public function run()
    {
        $this->processRequest();
    }
    
    public function processRequest()
    {
        $route = $this->getUrlManager()->parseUrl( $this->getRequest() );
        $this->createModule( $route );
    }  
    
    public function createModule( $route )
    {
        $moduleId   = $route['module'];
        $actionId   = $route['action'];
        $module     = $this->getModule( $moduleId );
        $actionName = "execute" . ucfirst( $actionId );
        $showName   = "show" . ucfirst( $actionId );
        $module->setCurrentActionName( $actionName );     
        $module->setCurrentShowName( $showName );     
    }
    
}