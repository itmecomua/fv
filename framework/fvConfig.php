<?php
class fvConfig {
	
	protected $configDir;
	protected $config;
	protected $configSeparator;

 	function __construct ($configDir, $configSeparator = ".") {
		$this->configDir = $configDir;
		$this->configSeparator = $configSeparator;
		$this->config = array();
	}

    
	protected function _parseValue($value) {
	    
	    $value = trim($value);
	    
	    if ($value == "~") {
	        return "";
	    }
	    
	    if ($value == "true") {
	        return true;
	    }
	    
	    if ($value == "false") {
	        return false;
	    }
	    
	    if (($value{0} == "[") && ($value{strlen($value) - 1} == "]")) {
	        $value = explode(",", substr($value, 1, strlen($value) - 2));
	        foreach ($value as &$oneValue) {
	            $oneValue = $this->_parseValue(trim($oneValue));
	        }
	        
	        return $value;
	    }
	    
	    if (($value{0} == "{") && ($value{strlen($value) - 1} == "}")) {
	        $value = explode(",", substr($value, 1, strlen($value) - 2));
	        
	        $result = array(); 
	        
	        foreach ($value as $oneValue) {
			    $a_key = substr($oneValue, 0, strpos($oneValue, ":"));
			    $a_value = substr($oneValue, strpos($oneValue, ":") + 1);
	            	            
	            $result[trim($a_key)] = $this->_parseValue(trim($a_value));
	        }
	        
	        return $result;
	    }
	    	    
	    if (preg_match_all("/%([a-z_0-9\.]+)%/i", $value, $matches, PREG_SET_ORDER)) {
	        foreach ($matches as $match) {
	            $value = str_replace($match[0], $this->get($match[1], (defined($match[1])?constant($match[1]):null)), $value);
	        }
	    }
	    
	    return $value;
	}
	
	protected function getFromArray($cPath, $data, $default = null) {
        $path = explode($this->configSeparator, $cPath);
        
        $result = $data;
        
        foreach ($path as $step) {
            if (isset($result[$step])) {
                $result = $result[$step];
            }
            else return $default;
        }
        
        return $result; 	    
	}
	
	protected function getCachedName($fileName) {
	    return FV_ROOT . "cache/config/" . ((defined('FV_APP'))?FV_APP . "/":'') . md5($fileName) . "cnf.php";
	}
	
    protected function checkConfig($fileName) {
	   $cacheName = $this->getCachedName($fileName);
	    
	   if (is_file($cacheName)) {
	        if (filectime($cacheName) < filectime($fileName)) return false;
	        include($cacheName);
	        return false;
	   } else {
	    
	       $dir = dirname($cacheName);
	       if (!is_dir($dir)) mkdir($dir, 0777);
	       return false;
	   }
	   
	   return false;
	}
	
	protected function putToCache($fileName, $configData) {
	    $data = '<?php
$this->mergeConfig(&$this->config, ' . var_export($configData, true) . ");";
	    file_put_contents($this->getCachedName($fileName), $data);
	}
	
	public function mergeConfig(&$currentConfig, $configArray) {
	    foreach ($configArray as $key => $value) {
	        if (is_array($value)) {
	            if (!isset($currentConfig[$key])) $currentConfig[$key] = array();
	            $this->mergeConfig($currentConfig[$key], $value);
	        } else {
	            $currentConfig[$key] = $value;
	        }
	    }
	}
	
	function Load($fileName, $fullPath = false) {
		      
        $configFile = ($fullPath)?$fileName:($this->configDir . $fileName);
        
	    
	    if (!file_exists($configFile)) throw new Exception("Can't load config file '".$this->configDir . $fileName."'. Terminating");
		
        //if (!$this->checkConfig($configFile)) {
		
		    ob_start();
    		include($configFile);
    		$configData = ob_get_contents();
    		ob_end_clean();
    		
    		//var_dump($configData);
    		
    		$configData = explode("\n", $configData);
    		$configArray = &$this->config;
    		$currentConfig = &$configArray;
    		$path = array();
    		
    		foreach ($configData as $configLine) {
    			if (strlen(trim($configLine)) == 0) continue;
    			
    			$indent = strlen($configLine) - strlen(ltrim($configLine));
    			$configLine = trim($configLine);
    			
    			foreach ($path as $key => $value) {
    			    if ($key > $indent)
    			        unset ($path[$key]);
    			}
    			
    			if (empty($path[$indent])) {
    			    $path[$indent] = &$currentConfig;    
    			} else {
    			    $currentConfig = &$path[$indent]; 
    			}
    			
    			$key = substr($configLine, 0, strpos($configLine, ":"));
    			$value = substr($configLine, strpos($configLine, ":") + 1);
    			
    			if (strlen($value = trim($value)) > 0) {
    			    $currentConfig[$key] = $this->_parseValue($value);
    			}
    			else {
    			    if (!isset($currentConfig[$key]))
    			        $currentConfig[$key] = array();
    			    $currentConfig = &$currentConfig[$key];
    			}
    			
    		}
    		
    		//$this->mergeConfig(&$this->config, $configArray);
    		/*var_dump(FV_ROOT);
    		var_dump($this->config);*/
    		
    		//$this->putToCache($configFile, $configArray);
        //}
    }
	
	function getAllConfig() {
		return $this->config;
	}
	
	function get($cPath, $default = null){
		$path = explode($this->configSeparator, $cPath);
		
		$result = $this->config;
		
		foreach ($path as $step) {
			if (isset($result[$step])) {
				$result = $result[$step];
			}
			else return $default;
		}
		
		return $result; 
	}
    
    public function getModuleName($module = false)
    {
        $module = $module ? $module : fvRequest::getInstance()->getRequestParameter('module');
        return $this->get('modules.'.$module.'.name');
    }
    
    function appendToConfig($name, $value)
    {
        $this->config[$name] = $value;
    }
}

?>





