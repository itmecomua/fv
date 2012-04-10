<?php

class fvCache 
{
	private $global_ttl;
	protected $configDir;
    protected $configTime;
    protected $configGlobalDir;
	
    function __construct () 
    {
		$this->configDir = fvSite::$fvConfig->get("path.cache");
        $this->configGlobalDir = fvSite::$fvConfig->get("path.global_cache");
        $this->configTime = fvSite::$fvConfig->get("cache.cachetime");
        $this->global_ttl = 1800;
        $this->setSerialSettings( $this->configDir, $this->global_ttl ); 
	}
    public static function getInstance() 
    {
        static $instance;

        if (!isset($instance)) {
             $instance = new self;
        }
        
        return $instance;
    }	
	function getCache($key, $time = null)
    {
        $LoggedUser = fvSite::$fvSession->get ( "login/loggedUser" );    
        if ($LoggedUser instanceof User && $LoggedUser->isRoot()) 
            return false;
            
        $serverName = $_SERVER["HTTP_HOST"];
        $key = strtolower($serverName.$key);
        
        $key = md5($key);
               
        if (!$time)
        {
            $time = $this->getConfigTime();
        }
        $filename =  $this->getConfigDir().$key.".cache";
        if (file_exists($filename))
        {
            if (time()-filemtime($filename)<$time)
            {
                return implode('', file ($filename));
            }
        }
		return false; 
	}
    function setCache($data, $module, $action, $key="")
    {
        $LoggedUser = fvSite::$fvSession->get ( "login/loggedUser" );    
        if ($LoggedUser instanceof User && $LoggedUser->isRoot()) 
            return false;
        $serverName = $_SERVER["HTTP_HOST"];         
        $key = strtolower($serverName.$module.$action.$key);
        $key = md5($key);
         
        $filename =  $this->getConfigDir().$key.".cache";
            if (!$handle = fopen($filename, 'w')) 
            {  
                return false;
            }

            if (fwrite($handle, $data) === FALSE) 
            {
                fclose($handle);
                return false;
            }
            fclose($handle);
            return true;
    }
    function getConfigTime()
    {
        return $this->configTime;
    }
    function getConfigDir()
    {
        return $this->configDir;
    }
    function delCacheByKey($key)
    {
        $serverName = $_SERVER["HTTP_HOST"];
        $key = strtolower($serverName.$key);
        
        $key = md5($key);  
        
        $filename = fvSite::$fvConfig->get("path.cache").$key.".cache";
        
        @unlink($filename);
    }
    function delCacheByFullKey($key) {                
        $key = strtolower($key);        
        $key = md5($key);          
        $filename = fvSite::$fvConfig->get("path.cache").$key.".cache";        
        @unlink($filename);
    }
    
    /*******************************          Global Cache             ***************************/
    function getGlobalCache($module, $action, $key)
    {
        /*$LoggedUser = fvSite::$fvSession->get ( "login/loggedUser" );    
        if ($LoggedUser instanceof User && $LoggedUser->isRoot()) 
            return false;
        */
            
        $module = strtolower($module);
        $action = strtolower($action);
        $key = md5($key);
        $key = $module."_".$action."_".$key;
        $time = $this->global_ttl;
         
        $filename =  $this->configGlobalDir.$key.".{$_ENV['USER']}cache";
        if (file_exists($filename))
        {
            if (time()-filemtime($filename)<$time)
            {
                return file_get_contents($filename);
            }
        }
        return false; 
    }    
    function setGlobalCache($data, $module, $action, $key="")
    {
        /*$LoggedUser = fvSite::$fvSession->get ( "login/loggedUser" );    
        if ($LoggedUser instanceof User && $LoggedUser->isRoot()) 
            return false;
        */
            
        $module = strtolower($module);
        $action = strtolower($action);
        $key = md5($key);
        $key = $module."_".$action."_".$key;    
        $filename =  $this->configGlobalDir.$key.".{$_ENV['USER']}cache"; 
        if (!$handle = fopen($filename, 'w')) return false;
        if (fwrite($handle, $data) === FALSE){fclose($handle);return false;}
        fclose($handle);
        return true;
    }
    function setSerialSettings($path, $ttl, $extension = "serial")
    {
        $this->_dirSerial = $path;
        $this->_ttlSerial = $ttl;
        $this->_extensionSerial = "." . $extension;
    }
    public function setSerialTTL( $ttl )
    {
        $this->_ttlSerial = $ttl;
        return $this;        
    }
    function setSerial($data,$name = false) 
    {        
        
        $trace = debug_backtrace(false);            
                
        if ($trace[1]["class"]) $filename[] = $trace[1]["class"];
        if ($trace[1]["function"]) $filename[] = $trace[1]["function"];
        if ($name) $filename[] = $name;        
        $filename = implode(".", $filename);
        $fullpath = $this->_dirSerial . $filename . $this->_extensionSerial;
        $fp = fopen($fullpath,"w");
        
        $serial = serialize($data);        
        if (fputs($fp,$serial))
            return true;
        
        return false;        
    }
    function getSerial($name = false) 
    {    
        
        if (count(explode(".",$name)) == 1) {
             $trace = debug_backtrace(false);                            
             if ($trace[1]["class"]) $filename[] = $trace[1]["class"];
             if ($trace[1]["function"]) $filename[] = $trace[1]["function"];
             if ($name) $filename[] = $name;        
             $filename = implode(".", $filename);        
        } else 
            $filename = $name;
           
        $fullpath = $this->_dirSerial . $filename . $this->_extensionSerial;                
        
        if (file_exists($fullpath) && (time()-filemtime($fullpath) < $this->_ttlSerial)) {
            $data = implode('', file ($fullpath));    
            $unserialize = unserialize($data);
            if ($unserialize)
                return $unserialize;
        }
               
        return false;
    }

}

?>
