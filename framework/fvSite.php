<?php
class fvSite
{
	private static $_classMapPaths;
    private static $_instances;
    private static $_config;
    private static $_dispatcher;
    
    public static function start( $config  )
	{
		self::startAutoloader( $config );
        self::startErrorHandler();
        self::setConfig( $config );
        self::setDispatcher();
        
        self::$_dispatcher->run();
    }
    
    private static function startAutoloader( $config )
    {
        self::$_classMapPaths = $config['classMapPaths'];
        spl_autoload_register( array( 'fvSite', 'autoload' ) );
    }
    
    public static function autoload( $className )
    {
        if( isset(self::$_classMapPaths[$className]) )
        {
            include(self::$_classMapPaths[$className]);
        }
    }
    
    private static function startErrorHandler()
    {
        error_reporting(E_ALL);
        set_exception_handler( 'fvErrorHandler::ExceptionHandler' );
        set_error_handler( 'fvErrorHandler::ErrorHandler' , E_ALL );
    }

    private static function setConfig( $config )
    {
        self::$_config = self::getSingleton( 'fvConfig' , $config );
    }
    
    public static function getConfig()
    {
        return self::$_config;
    }
    
    private static function setDispatcher()
    {
        self::$_dispatcher = self::getSingleton('fvDispatcher');
    }
    
    public static function getDispatcher()
    {
        return self::$_dispatcher;
    }
    
    public static function Import( $path, $className=null )
    {
        if( $className == null )
        {
            $separator = self::getConfig()->getConfigSeparator();
            $extension = self::getConfig()->getExecuteFileExtension();
            $cutterpos = strrpos( $path , $separator );
            $className = trim( substr( $path , $cutterpos) , $separator );
            if(isset(self::$_classMapPaths[$className]))
            {
                return  self::$_classMapPaths[$className];
            }        
            else
            {
                $fileName  = $className . $extension;       
                $classPath = self::getConfig()->getPathByAlias( substr( $path , 0 , $cutterpos) ) . $fileName;
                self::$_classMapPaths[$className] = $classPath;
            }
        }
        else
        {
            self::$_classMapPaths[$className] = $path;
        }
    }

    public static function getSingleton( $name , $params=null )
    {
        if(isset(self::$_instances[$name]))
        {
            return  self::$_instances[$name];
        }
        else
        {
            self::$_instances[$name] = new $name( $params );
            if( self::$_instances[$name] instanceof fvUnit ) 
            {                
                if( self::getConfig()->isSeting( $name ) )
                {
                    self::$_instances[$name]->configure( self::getConfig()->getSeting( $name ) );
                    self::$_instances[$name]->init();
                }
                
            }
            return self::$_instances[$name];
        }
    }

    public static function Debug( $d )
    {
        echo "<pre>";
        print_r($d);
        echo "</pre>";
    }
    
    

}
