<?php
class fvModule extends fvSegment
{
    private $_currentActionName;
    private $_currentShowName;
    
    public function setCurrentActionName( $currentActionName )
    {
        $this->_currentActionName = $currentActionName;
    }
    
    public function getCurrentActionName()
    {
        return $this->_currentActionName;
    }        
    
    public function setCurrentShowName( $currentShowName )
    {
        $this->_currentShowName = $currentShowName;
    }
    
    public function getCurrentShowName()
    {
        return $this->_currentShowName;
    }        
    
}