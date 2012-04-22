<?php
class fvConfig {
	protected $config;
	protected $configSeparator;

 	public function __construct ($config, $configSeparator = ".") {
		$this->config = $config;
        $this->configSeparator = $configSeparator;
	}

    public function get($cPath, $default = null){
        $path = explode($this->configSeparator, $cPath);
        $result = $this->config;
        foreach ($path as $step) 
        {
            if (isset($result[$step])) 
            {
                $result = $result[$step];
            }
            else
            {
                throw new Exception("Can't read value from config '". $step . "'. Terminating");                  
            }
        }
        return $result; 
    }

	function Load( $fileName ) 
    {
        if ( file_exists($fileName) ){
            $this->config  = array_merge( $this->config , $fileName );
        }else{
            throw new Exception("Can't load config file '". $fileName . "'. Terminating");  
        } 
	}
	
	function getAllConfig() {
		return $this->config;
	}
    
    function appendToConfig($name, $value)
    {
        $this->config[$name] = $value;
    }
}