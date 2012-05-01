<?php
class fvRequest extends fvUnit{
    private $_serverUrlHolder;
    
    protected function setServerUrlHolder( $value )
    {
        $this->_serverUrlHolder = $value;
    }
    
    private function getServerUrlHolder()
    {
        return $this->_serverUrlHolder;
    }
        
    public function getUrl()
    {
        return $_SERVER[$this->getServerUrlHolder()];
    }
    

}