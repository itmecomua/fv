<?php
class fvApplication
{
    private $_route;
    private $_basepath;
    private $_urlManager;
    private $_request;
    
    public function __construct($appname)
    {
        fvSite::setApplication($this);
        fvSite::getConfig()->Load( FvSite::getPathOfAlias($appname."config") );
        fvSite::setPathOfAlias('webroot', FV_ROOT);
		fvSite::setPathOfAlias('basePath',  FvSite::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . FvSite::getPathOfAlias('app') . DIRECTORY_SEPARATOR . $appname);
        $this->_urlManager = new fvUrlManager();
        $this->_urlManager = new fvRequest();
/*
		$this->initSystemHandlers();
		$this->registerCoreComponents();
		$this->configure($config);
		$this->preloadComponents();
*/
    }
	
	public function run()
	{
		$this->processRequest();
	}	
    
	public function processRequest()
	{
	    echo $route=$this->getUrlManager()->parseUrl($this->getRequest());
	}    
    
    public function getUrlManager()
    {
        return $this->_urlManager;
    }
    
    public function getRequest()
    {
        return $this->_request;
    }
    
/* *********************************************************************************************************  */    

    public function getCommand()
    {
        
    }

    public function getView()
    {
        
    }
    
    function getModule($module, $type) 
    {
        if (!class_exists($class = fvSite::$fvConfig->get("modules.{$module}.{$type}_class"))) 
        {
            if (file_exists(fvSite::$fvConfig->get("modules.{$module}.path") . "{$type}.class.php")) 
            {
                require_once(fvSite::$fvConfig->get("modules.{$module}.path") . "{$type}.class.php");
            }
            else 
            {
                require_once(fvSite::$fvConfig->get("modules.staticpages.path") . "{$type}.class.php");
                $class = fvSite::$fvConfig->get("modules.staticpages.{$type}_class");
            }
        }
        return new $class;
    }
}