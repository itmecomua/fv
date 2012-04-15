<?php
/*
*  1) Установака автозагрузчика
*  2) Инициализация различных частей движка
*  3) Создание диспетчера и передача ему управления
*/
class fvSite{
    private static $_classMap       = array();
    private static $_aliases        = array();
    private static $_includePaths   = array();
    
    private static $_fvConfig;
    private static $_Db;
    private static $_fvSession;
    private static $_fvDispatcher;
   
    public static function start( $config = null ){     
       self::startAutoload($config);
       self::startConfig($config);
       self::startDb();
       self::startSession();
       self::startTemplateEngine();

       self::$_fvDispatcher = new fvDispatcher();
       self::$_fvDispatcher->process();
    }
    
    private static function startAutoload($config){
       self::$_classMap     = $config['classmap'];
       self::$_aliases      = $config['aliases'];
       self::$_includePaths = $config['includepaths'];
       spl_autoload_register( array('fvSite','autoload') );        
    }
    
    private static function startConfig($config){
       self::$_fvConfig = new fvConfig($config);
    }
    
    private static function startDb(){
       
    }   
    
    private static function startSession(){
        
    }
    
    private static function startTemplateEngine(){
        
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
        *   если есть в масиве - берем из массива
        */
        if(isset(self::$_classMap[$className]))
        {
            include(self::$_classMap[$className]);
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
                        self::$_classMap[$className] = $classFile;
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
    
    public static function getConfig(){
        return self::$_fvConfig;
    }
   
}