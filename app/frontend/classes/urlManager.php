﻿<?php
class urlManager{
	
	private $_caseSensetive;
    
    public function setCaseSensetive( $value )
    {
        $this->_caseSensetive = $value;
    }
    
    public function getCaseSensetive()
    {
        return $this->_caseSensetive;
    }
    
    /*
    * Должен вернуть имя модуля, имя акшина, массив параметров
    */
    public function parseUrl( fvRequest $request )
	{
		$requestUrl = $request->getUrl();
        $appName    = fvSite::getDispatcher()->getAppName();
        $route      = str_replace( $appName , "" , $requestUrl );
        $route      = explode( "/" , $route );
        
        $module     = (isset($route[0]))?($route[0]):(fvSite::getConfig()->getSeting('defaultModule'));
        $action     = (isset($route[1]))?($route[1]):(fvSite::getConfig()->getSeting('defaultAction'));
        
        if( sizeof( $route ) )
        
        
        return array( 'module' => $module , 'action'=>  $action , 'params' => $params );
	}
}