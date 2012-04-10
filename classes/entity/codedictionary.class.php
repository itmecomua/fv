<?php
require_once (fvSite::$fvConfig->get("path.entity") . 'dictionary_manager.class.php') ;
class CodeDictionary extends Dictionary
{
    
    function __construct () 
    {
        $this->currentEntity = __CLASS__;
        parent::__construct(fvSite::$fvConfig->get("entities.{$this->currentEntity}.fields"),
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.table_name"),
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.primary_key", "id"));
    }
    
    public function validateCode($value)
    {
        $valid = $this->doValidateEmpty($value);
        $this->setValidationResult('code', $valid, "Поле обязательное");
        return $valid;
    }
    
    public function getDictionaryName()
    {
        return 'Код';
    }
    
    /**
    * Получить название
    * @author Nesterenko Nikita
    * @since 2011/11/02
    * @return string
    */    
    public function getName()
    {
        return $this->name;
    }
    /**
    * Получить название позиции
    * @author Nesterenko Nikita
    * @since 2011/11/02
    * @return string
    */
    public function getPositionName()
    {
        $pos = $this->getManager()->getPosition($this->position_id);
        return !is_array($pos) ? $pos : "Не указана";
    }
    /**
    * Метка активен или как ?
    * @author Nesterenko Nikita
    * @since 2011/11/02
    * @return bool
    */
    public function isActive()
    {
        return (bool)$this->is_active;
    }
    /**
    * Получить код
    * @author Nesterenko Nikita
    * @since 2011/11/02
    * @return string
    */
    public function getCode()
    {
        return $this->code;
    }
   
}
