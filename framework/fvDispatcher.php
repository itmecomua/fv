<?php
class fvDispatcher
{
    private $_request;
    private $_appName;
    private $_app;
    
    public function run()
    {
        $this->setRequest();
        $this->setAppName( $this->resolveAppName( $this->getRequest()->getUrl() ) );        
        $this->setApp( $this->getAppName() );
        $this->getApp()->run();
        $this->process();
    }      
    
    private function setRequest()
    {
        $this->_request = fvSite::getInstance('fvRequest');
    }
    
    public function getRequest()
    {
        return $this->_request;
    }
    
    private  function setAppName( $appName )
    {
        $this->_appName = $appName;
    }
    
    public  function getAppName()
    {
        return $this->_appName;
    }
    
    private  function setApp( $appName )
    {
        $this->_app = fvSite::getInstance('fvApplication' , $appName );
    }
    
    public  function getApp()
    {
        return $this->_app;
    }
    
    private function resolveAppName( $url )
    {
        $appNameArr = explode("/" , trim($url , "/") );
        $appName = $appNameArr[0];
        if( in_array( $appName , fvSite::getConfig()->getSeting('appList') ) )
        {
            return $appName;
        }
        else
        {
            return fvSite::getConfig()->getSeting('appDefault');    
        }

    }
    
    private function process()
    {
        
    }
    
    
}