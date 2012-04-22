<?php
/*
*  1) Считывание конфига Application
*  2) Считывание роутов Application
*  3) Считывание 'модулей' Application
*  4) Инициализация классов Application
*  5) Получение модуля 
*  6) Получения Акшина 
*  7) Получения фильтров
*  8) Получения шаблона
*  9) Получения страницы 
*/    

class fvApplication
{
    private $_route;
    private $_basepath;
    public function __construct($appname){
			
		throw new Exception("new Exception");                  
        
        fvSite::setApplication($this);

		// set basePath at early as possible to avoid trouble
		if(is_string($config))
			$config=require($config);
		if(isset($config['basePath']))
		{
			$this->setBasePath($config['basePath']);
			unset($config['basePath']);
		}
		else
			$this->setBasePath('protected');
		Yii::setPathOfAlias('application',$this->getBasePath());
		Yii::setPathOfAlias('webroot',dirname($_SERVER['SCRIPT_FILENAME']));
		Yii::setPathOfAlias('ext',$this->getBasePath().DIRECTORY_SEPARATOR.'extensions');

		$this->preinit();

		$this->initSystemHandlers();
		$this->registerCoreComponents();

		$this->configure($config);
		$this->attachBehaviors($this->behaviors);
		$this->preloadComponents();

		$this->init(); 
            
			//Load main application config
            //Load routes for application
            //Load modules config
            //Load app classes        
    }
    
    public function process()
    {
        

    }   
	
	public function run()
	{
		if($this->hasEventHandler('onBeginRequest'))
			$this->onBeginRequest(new CEvent($this));
		$this->processRequest();
		if($this->hasEventHandler('onEndRequest'))
			$this->onEndRequest(new CEvent($this));
	}	
    
	public function processRequest()
	{
		if(is_array($this->catchAllRequest) && isset($this->catchAllRequest[0]))
		{
			$route=$this->catchAllRequest[0];
			foreach(array_splice($this->catchAllRequest,1) as $name=>$value)
				$_GET[$name]=$value;
		}
		else
			$route=$this->getUrlManager()->parseUrl($this->getRequest());
		$this->runController($route);
	}    
    
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
            else {
                require_once(fvSite::$fvConfig->get("modules.staticpages.path") . "{$type}.class.php");
                $class = fvSite::$fvConfig->get("modules.staticpages.{$type}_class");
            }
        }
        return new $class;
    }    

    
}