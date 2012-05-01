<?php
class fvApplication extends fvUnit
{  
    private $_request;
    private $_urlManager;
    private $_defaultController;
    
    private $controllerMap;
    private $_modulesIncluded;
    private $_modules;

        
    public function __construct( $appName )
    {
        fvSite::getConfig()->setPathByAlias('currentApp' , fvSite::getConfig()->getPathByAlias('app') . $appName ) ;
        fvSite::getConfig()->Load( fvSite::getConfig()->getPathByAlias('currentApp.config') . "app.php" );
        $this->setUrlManager();
        $this->setRequest();
    }

    private function setUrlManager()
    {
        fvSite::Import( 'currentApp.classes.urlManager' );
        $this->_urlManager = fvSite::getInstance('urlManager');
    }
    
    private function getUrlManager()
    {
        return $this->_urlManager;
    }
    
    private function setRequest()
    {
        $this->_request = fvSite::getInstance('fvRequest');
    }
    
    private function getRequest()
    {
        return $this->_request;
    }
       
    public function setDefaultController( $name )
    {
        $this->_defaultController = $name;
    }  

    public function getDefaultController()
    {
        return $this->_defaultController;
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

/*
* TODO:
*/    
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
            $this->_modules[$id] = fvSite::getInstance( $currentModuleClassName );
            $this->_modules[$id]->setBasePath( $currentAppModulesDir . $currentModuleDir );
            return $this->_modules[$id];
        }

    }    
    
/*
* TODO:
*/
    public function getControllerPath()
    {
        echo "XXX";
        return FV_ROOT;
    }
    public function run()
    {
        $this->processRequest();
    }
    
    public function processRequest()
    {
        $route = $this->getUrlManager()->parseUrl( $this->getRequest() );
        $this->runController( $route );
    }  
    
    public function runController( $route )
    {
        $ca = $this->createController($route);
/*
        if( $ca !== null )
        {
            list($controller,$actionID)=$ca;
            $oldController=$this->_controller;
            $this->_controller=$controller;
            $controller->init();
            $controller->run($actionID);
            $this->_controller=$oldController;
        }
        else
        {
            throw new Exception( 'Unable to resolve the request ' . $route , 404 );
        }
*/        
    }
    
    public function createController( $route, $owner=null )
    {
        // если владельца нет - владелец Application 
        if( $owner === null ){ $owner = $this; }
        
        // если $route - пустота то тогда $route - контроллер по умолчанию
        if( ( $route = trim( $route,'/' ) ) === '' ){ $route = $owner->getDefaultController; }
        
        // узнаем указана ли регистровая чувствительность URL ...
        $caseSensitive = $this->getUrlManager()->getCaseSensetive();
        
        // прибавляем слеш в конец $route
        $route.='/';

        // пока $route содержит в себе '/'...
        while( ( $pos = strpos( $route, '/') ) !== false )
        {
            // вырезаем кусок строки от начала строки до позиции в которой обнаружен первый слеш
            $id = substr( $route, 0, $pos );

            // если этот участок содержит ерунду - возвращаем нуль
            if(!preg_match('/^\w+$/',$id)){ return null; }
                
            // если НЕ установленна чувствительность URL к регистру - уменьшаем все буквы в куске
            if(!$caseSensitive){ $id=strtolower($id); }
                
            // вырезаем другой кусок, от первой точки до конца строки
            $route = (string) substr( $route, $pos+1 );
                        
            // если не установленна переменная $basePath
            if(!isset($basePath))  // first segment
            {
                // если в реестре владельца существует запись с настоящим  $id
                if( isset( $owner->controllerMap[$id] ) )
                {
                    return array(
                        Yii::createComponent($owner->controllerMap[$id],$id,$owner===$this?null:$owner),
                        $this->parseActionParams($route),
                    );
                }
                
                // Если существует модуль с таким названием то рекурсивно вызваем этот же метод только уже с параметром $module
                if( ( $module = $owner->getModule($id) ) !==null )
                {
                    return $this->createController( $route, $module );
                }
                    

                $basePath = $owner->getControllerPath();
                $controllerID='';
                
            }
            else
            {
                $controllerID.='/';
            }
                
            $className=ucfirst($id).'Controller';
            $classFile=$basePath.DIRECTORY_SEPARATOR.$className.'.php';
            if(is_file($classFile))
            {
                if(!class_exists($className,false))
                    require($classFile);
                if(class_exists($className,false) && is_subclass_of($className,'CController'))
                {
                    $id[0]=strtolower($id[0]);
                    return array(
                        new $className($controllerID.$id,$owner===$this?null:$owner),
                        $this->parseActionParams($route),
                    );
                }
                return null;
            }
            $controllerID.=$id;
            $basePath.=DIRECTORY_SEPARATOR.$id;
        }
    }
    
    
}
