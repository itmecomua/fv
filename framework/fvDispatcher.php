<?php
class fvDispatcher
{
    private $_request;
    private $_appName;
    private $_app;
    
    public function run()
    {
        $this->setRequest();
        $this->setAppName( $this->resolveAppName( $this->getRequest()->getUrl() ) );        
        $this->setApp( $this->getAppName() );
        $this->getApp()->run();
        $this->process();
    }      
    
    private function setRequest()
    {
        $this->_request = fvSite::getSingleton('fvRequest');
    }
    
    public function getRequest()
    {
        return $this->_request;
    }
    
    private  function setAppName( $appName )
    {
        $this->_appName = $appName;
    }
    
    public  function getAppName()
    {
        return $this->_appName;
    }
    
    private  function setApp( $appName )
    {
        $this->_app = fvSite::getSingleton('fvApplication' , $appName );
    }
    
    public  function getApp()
    {
        return $this->_app;
    }
    
    private function resolveAppName( $url )
    {
        $appNameArr = explode("/" , trim($url , "/") );
        $appName = $appNameArr[0];
        if( in_array( $appName , fvSite::getConfig()->getSeting('appList') ) )
        {
            return $appName;
        }
        else
        {
            return fvSite::getConfig()->getSeting('appDefault');    
        }

    }
    
    private function process()
    {
        $modules = $this->getApp()->getAllModules();
        foreach( $modules as $module )
        {
            $this->invokeView( $module );
        }
    }
    
    private function invokeView( $module )
    {
        $_viewFile =  $module->getBasePath() . "/views/".$module->getModuleId().".".$module->getActionId().".tpl";
        $actionMethod = $module->getShowName();
        $_data_ = $module->$actionMethod();
        extract($_data_, EXTR_PREFIX_SAME,'data');
        ob_start();
        ob_implicit_flush(false);
        require($_viewFile);
        echo ob_get_clean();        
        
    }    
    
    public function _createController( $route, $owner=null )
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
