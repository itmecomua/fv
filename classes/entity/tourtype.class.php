<?php
  
  class TourType extends Dictionary
  {
    
    protected $currentEntity = '';
    
    function __construct () 
    {
        
        $this->currentEntity = __CLASS__;
        parent::__construct(fvSite::$fvConfig->get("entities.{$this->currentEntity}.fields"), 
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.table_name"), 
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.primary_key", "id"),
                            $this->currentEntity);
    }
        
    function validateName($value) 
    {
        $valid = true;
        if (strlen($value)>0)
        {
            $exist = TourTypeManager::getInstance()->getOneByname($value);    
            if(TourTypeManager::getInstance()->isRootInstance($exist)) {
                $valid = false;
                $msg = "Запись с таким названием уже существует";
            }
        }
        else
        {
            $msg = "Это поле обязательное";
            $valid = false;
        }
        
        $this->setValidationResult('name', $valid,$msg);
        return $valid;
        
    }

    public function getDictionaryName()
    {
        return 'Тип тура';
    }
    /**
    * Получить URL просмотра информации о стране
    * 
    * @return string
    */ 
    public function getViewURL()
    {
        return "/tours/list/{$this->getURL()}";
    }
    public function getURL()
    {
        return $this->hasField('url') ? $this->url : 'no such field';        
    }
    public function getName()
    {
        return $this->hasField('name') ? $this->name : 'no such field';        
    }
    public function getShortText()
    {
        return $this->hasField('short_text') ? $this->short_text : 'no such field';        
    }    


  
}