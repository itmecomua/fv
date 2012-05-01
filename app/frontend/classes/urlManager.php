<?php
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
    
    
    public function parseUrl( fvRequest $request )
	{
		$requestUrl = $request->getUrl();
        $appName    = fvSite::getDispatcher()->getAppName();
        $route      = str_replace( $appName , "" , $requestUrl );
        
        return $route;
	}
}