<?php
class fvConfig {
	private $config;
	private $configSeparator;
    private $executeFileExtension;

 	public function __construct ($config, $configSeparator = ".") 
    {
		$this->config = $config;
        $this->configSeparator = $configSeparator;
        $this->executeFileExtension = ".php";
	}

    private function get($cPath, $default = null)
    {
        $path       = explode($this->configSeparator, $cPath);
        $value      = $this->config;
        $errorFlag  = false;
        $errorPoint = "";

        foreach ($path as $step) 
        {
            if (isset($value[$step])) 
            {
                $value = $value[$step];
            }
            else
            {
                $errorFlag  = true;
                $errorPoint = $step;
            }
        }
        return array( 'value'=>$value, 'errorFlag'=>$errorFlag, 'errorPoint'=>$errorPoint );
    }
    
    public function getSeting($cPath, $default = null)
    {
        $result = $this->get($cPath, $default);
        if( $result['errorFlag'] )
        {
            throw new Exception( 'Can not read value '.$result['errorPoint'].' from config' ); 
        }
        else
        {
            return $result['value'];    
        }
    }

    public function isSeting( $cPath, $default = null )
    {
        $result = $this->get($cPath, $default);
        return !$result['errorFlag'];
    }
       
	function Load( $fileName ) 
    {
        if ( file_exists( $fileName ) ){
            $this->config  = array_merge_recursive( $this->config , require( $fileName ) );
        }else{
            throw new Exception("Can't load config file '". $fileName . "'. Terminating");  
        } 
	}
    
    public function getPathByAlias( $alias )
    {
        if( isset( $this->config['aliasMapPaths'][$alias] ) )
        {
            return $this->config['aliasMapPaths'][$alias];
        }           
        else
        {
            $aliasStruct = explode($this->configSeparator, $alias );
            $realPath = "";
            
            foreach($aliasStruct as $step)
            {
                if( isset( $this->config['aliasMapPaths'][$step] ) )
                {
                    $stepPath = str_replace( '/' , DIRECTORY_SEPARATOR , $this->config['aliasMapPaths'][$step] );                   
                    $stepPath = trim( $stepPath , DIRECTORY_SEPARATOR );
                    $realPath .=  $stepPath . DIRECTORY_SEPARATOR ;
                }
                else
                {
                    throw new Exception("Can't load alias '". $step . "'. Terminating");      
                }
            }
            return $realPath;
        }
    }
    
    public function setPathByAlias( $alias , $path )
    {
        if(empty($path))
        {
            unset( $this->config['aliasMapPaths'][$alias] );
        }           
/*
        else if( strpos( $path , $this->getConfigSeparator() ) )
        {
            $this->config['aliasMapPaths'][$alias] = $this->getPathByAlias( trim( $path , $this->getConfigSeparator() ) );
        }
*/        
        else
        {
            $this->config['aliasMapPaths'][$alias] = rtrim( $path, '\\/' );    
        }
    }
    
    public function getConfigSeparator()
    {
        return $this->configSeparator;
    }
    
    public function getExecuteFileExtension()
    {
        return $this->executeFileExtension;
    }
    
    public function getAllConfig()
    {
        return $this->config;
    }
    
	   
}
