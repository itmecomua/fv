<?php
class fvSegment extends fvUnit
{
    private $_basePath;
    
    public function setBasePath( $path )
    {
        $this->_basePath = $path;
    }
    
    public function getBasePath()
    {
        return $this->_basePath;
    }        
}