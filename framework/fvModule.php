<?php
class fvModule extends fvSegment
{
    private $_moduleId;
    private $_actionId;
    private $_actionName;
    private $_showName;
    

    public function setModuleId( $moduleId )
    {
        $this->_moduleId = $moduleId;
    }
    
    public function getModuleId()
    {
        return $this->_moduleId;
    }        
    
    public function setActionId( $actionId )
    {
        $this->_actionId = $actionId;
    }
    
    public function getActionId()
    {
        return $this->_actionId;
    }        

    public function setActionName( $actionName )
    {
        $this->_actionName = $actionName;
    }
    
    public function getActionName()
    {
        return $this->_actionName;
    }        
    
    public function setShowName( $showName )
    {
        $this->_showName = $showName;
    }
    
    public function getshowName()
    {
        return $this->_showName;
    }        
    
}