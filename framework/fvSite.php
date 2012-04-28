<?php
class fvSite{
    private static $_classMapPaths  = array();
    private static $_aliases        = array();
    private static $_includePaths   = array();
    private static $_fvConfig;
    private static $_fvDispatcher;    
	private static $_app;
    
    public static function start( $config = null )
    {   
        if(self::isLocal()){
            self::localStart($config);
        }else{
            self::productionStart($config);
        }
    }        
    
    private static function isLocal()
    {
        $srv = $_SERVER['SERVER_NAME'];
        return ($srv === "lab3.own" );
    }
    
    private static function localStart( $config )
    {
        self::process($config);
    }
    
    private static function productionStart( $config )
    {
        self::startExceptionGate();
        try
        {        
            self::process($config);
        }
        catch(Exception $ExceptionObj)
        {
           self::ExceptionGate($ExceptionObj);
        }            
    }
    
    private static function process( $config )
    {
           self::startAutoload($config);
           self::startConfig($config);
           self::$_fvDispatcher = new fvDispatcher();
           self::$_fvDispatcher->process();        
    }    

    private static function startExceptionGate()
    {
        error_reporting(E_ALL);
        set_error_handler("self::ExceptionGateWraper");    
    }
    
    private static function ExceptionGateWraper( $errno, $errstr, $errfile, $errline )
    {
            $errorString = "ERROR was detected automaticly : " . $errno ." ". $errstr ." ". $errfile ." ". $errline . "<br>" ;
            throw new Exception( $errorString );
    }

    private static function ExceptionGate($ExceptionObj)
    {
        echo "ExceptionGate <br>";
        echo $ExceptionObj->getMessage();
    }
        
    private static function startAutoload($config)
    {
       self::$_classMapPaths    = $config['classmapPaths'];
       self::$_aliases          = $config['aliases'];
       self::$_includePaths     = $config['includepaths'];
       spl_autoload_register( array('fvSite','autoload') );        
    }
       
    public static function autoload($className)
    {
        /*
        *  TODO :
        * вместо того что бы использовать standAlone (getInstance)
        * блокируем создание обьектов в этом методе (создаем один раз и сохраняем ссылку)
        * классы которые будут создаваться только один раз - пишем в файл конфигурации
        * (ой шо то мне кажетсо шо ничо из этого не выйдет)
        */
        
        
        /*
        *   если путь к классу есть в масиве - берем из массива
        */
        if(isset(self::$_classMapPaths[$className]))
        {
            include(self::$_classMapPaths[$className]);
        }
        else
        {
            /*
            *   Если нет в массиве и запрос НЕ на пространство имен (namespace  PHP 5.3)
            *   ищем в "путях включения"
            */
            if(strpos($className,'\\')===false)
            {
                foreach(self::$_includePaths as $path)
                {
                    $classFile=$path.DIRECTORY_SEPARATOR.$className.'.php';
                    if(is_file($classFile))
                    {
                        self::$_classMapPaths[$className] = $classFile;
                        include($classFile);
                        break;
                    }
                }
            }
            else 
            {
                /*
                *   Если нет в массиве и запрос на пространство имен (namespace  PHP 5.3)
                *   отдаем что есть
                */
                $namespace=str_replace('\\','.',ltrim($className,'\\'));
                if( ($path=self::getPathOfAlias($namespace))!==false ){
                    include($path.'.php');
                }
                else{
                    return false;
                }
                    
            }
            return class_exists($className,false) || interface_exists($className,false);
        }
        return true;
    }
    
    private static function startConfig($config)
    {
       self::$_fvConfig = new fvConfig($config);
    }
    
    public static function getPathOfAlias($alias)
    {
        if(isset(self::$_aliases[$alias]))
        {
            return self::$_aliases[$alias];
        }
        else if( ($pos=strpos($alias,'.'))!==false )
        {
            $rootAlias=substr($alias,0,$pos);
            if(isset(self::$_aliases[$rootAlias]))
            {
                return self::$_aliases[$alias]=rtrim(self::$_aliases[$rootAlias].DIRECTORY_SEPARATOR.str_replace('.',DIRECTORY_SEPARATOR,substr($alias,$pos+1)),'*'.DIRECTORY_SEPARATOR);
            }
        }
        return false;
    }

    public static function setPathOfAlias($alias,$path)
    {
        if(empty($path)){
            unset(self::$_aliases[$alias]);
        }else{
            self::$_aliases[$alias]=rtrim($path,'\\/');
        }
    }    
    
    public static function getConfig()
    {
        return self::$_fvConfig;
    }
	
	public static function setApplication($app)
	{
		if(self::$_app===null || $app===null)
			self::$_app=$app;
		else
			throw new FvException(Yii::t('yii','Yii application can only be created once.'));
	}
    
    public static function createComponent($config)
    {
        if(is_string($config))
        {
            $type=$config;
            $config=array();
        }
        else if(isset($config['class']))
        {
            $type=$config['class'];
            unset($config['class']);
        }
        else
            throw new FvException(Yii::t('yii','Object configuration must be an array containing a "class" element.'));

        if(!class_exists($type,false))
            $type=Yii::import($type,true);

        if(($n=func_num_args())>1)
        {
            $args=func_get_args();
            if($n===2)
                $object=new $type($args[1]);
            else if($n===3)
                $object=new $type($args[1],$args[2]);
            else if($n===4)
                $object=new $type($args[1],$args[2],$args[3]);
            else
            {
                unset($args[0]);
                $class=new ReflectionClass($type);
                // Note: ReflectionClass::newInstanceArgs() is available for PHP 5.1.3+
                // $object=$class->newInstanceArgs($args);
                $object=call_user_func_array(array($class,'newInstance'),$args);
            }
        }
        else
            $object=new $type;

        foreach($config as $key=>$value)
            $object->$key=$value;

        return $object;
    }    

    public static function import($alias, $forceInclude=false)
    {
        if( isset(self::$_imports[$alias]) )
        {
            return self::$_imports[$alias];            
        }  

        if( class_exists($alias, false) || interface_exists($alias, false) )
        {
            return self::$_imports[$alias]=$alias;            
        }

        // a class name in PHP 5.3 namespace format
        if(($pos=strrpos($alias,'\\'))!==false)
        {
            $namespace=str_replace('\\','.',ltrim(substr($alias,0,$pos),'\\'));
            if(($path=self::getPathOfAlias($namespace))!==false)
            {
                $classFile=$path.DIRECTORY_SEPARATOR.substr($alias,$pos+1).'.php';
                if($forceInclude)
                {
                    if(is_file($classFile))
                        require($classFile);
                    else
                        throw new FvException(Yii::t('yii','Alias "{alias}" is invalid. Make sure it points to an existing PHP file.',array('{alias}'=>$alias)));
                    self::$_imports[$alias]=$alias;
                }
                else
                    self::$classMap[$alias]=$classFile;
                return $alias;
            }
            else
                throw new FvException(Yii::t('yii','Alias "{alias}" is invalid. Make sure it points to an existing directory.',
                    array('{alias}'=>$namespace)));
        }

        if(($pos=strrpos($alias,'.'))===false)  // a simple class name
        {
            if($forceInclude && self::autoload($alias))
                self::$_imports[$alias]=$alias;
            return $alias;
        }

        $className=(string)substr($alias,$pos+1);
        $isClass=$className!=='*';

        if($isClass && (class_exists($className,false) || interface_exists($className,false)))
            return self::$_imports[$alias]=$className;

        if(($path=self::getPathOfAlias($alias))!==false)
        {
            if($isClass)
            {
                if($forceInclude)
                {
                    if(is_file($path.'.php'))
                        require($path.'.php');
                    else
                        throw new FvException(Yii::t('yii','Alias "{alias}" is invalid. Make sure it points to an existing PHP file.',array('{alias}'=>$alias)));
                    self::$_imports[$alias]=$className;
                }
                else
                    self::$classMap[$className]=$path.'.php';
                return $className;
            }
            else  // a directory
            {
                if(self::$_includePaths===null)
                {
                    self::$_includePaths=array_unique(explode(PATH_SEPARATOR,get_include_path()));
                    if(($pos=array_search('.',self::$_includePaths,true))!==false)
                        unset(self::$_includePaths[$pos]);
                }

                array_unshift(self::$_includePaths,$path);

                if(self::$enableIncludePath && set_include_path('.'.PATH_SEPARATOR.implode(PATH_SEPARATOR,self::$_includePaths))===false)
                    self::$enableIncludePath=false;

                return self::$_imports[$alias]=$path;
            }
        }
        else
            throw new FvException(Yii::t('yii','Alias "{alias}" is invalid. Make sure it points to an existing directory or file.',
                array('{alias}'=>$alias)));
    }    
   
}
