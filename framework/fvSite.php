<?php
class fvSite{
    private static $_classMap       =array();
    private static $_aliases        =array();
    private static $_includePaths   =array();
    
    private static $_fvConfig;
    private static $_Db;
    private static $_fvSession;
    private static $_Template;
    private static $_currentModules;
    private static $_Layoult;
    private static $_fvRequest;
    private static $_fvParams;    
    private static $_fvDispatcher;
   
    public static function start($config=null){     
       self::startAutoload($config);
       self::startConfig($config);
       self::startDb();
       self::startSession();
       self::startTemplateEngine();
       
       self::$_fvDispatcher = new fvDispatcher();
       self::$_fvDispatcher->process();
    }
    private static function startAutoload($config){
       self::$_classMap = $config['classMap'];
       self::$_aliases = $config['aliases'];
       self::$_includePaths = $config['includePaths'];
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
        *   ���� ���� � ������ - ����� �� �������
        */
        if(isset(self::$_classMap[$className]))
        {
            include(self::$_classMap[$className]);
        }
        else
        {
            /*
            *   ���� ��� � ������� � ������ �� �� ������������ ���� (namespace  PHP 5.3)
            *   ���� � "����� ���������"
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
                *   ���� ��� � ������� � ������ �� ������������ ���� (namespace  PHP 5.3)
                *   ������ ��� ����
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
    
    public static function getfvConfig(){
        return self::$_fvConfig;
    }
   
}