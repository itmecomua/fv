<?php
class fvModel extends fvUnit
{
    private $_entityManager;
    
    public function init()
    {
        $this->_entityManager = fvSite::getDispatcher()->getApp()->getDb()->getEntityManager();
    }
}