<?php
    
class EUserMessageError extends Exception {
    const NOT_FOUND_ROW = 'Не найдена запись. Обновите пожалуйста страницу.';
    const M_NOT_VALID = 'Не валидные данные';
    
    public $instance;
    public $prefix;
    function __construct($message,$instance = null,$prefix = '' )
    {
        $this->setInstance($instance);
        $this->prefix = $prefix;
        parent::__construct($message);
    }
    public function setInstance($instance)
    {
        $this->instance = $instance;
    }
    function getValidationResult()
    {
        $return = array();
        if ( is_object($this->instance) && method_exists($this->instance,"getValidationResult") ) {
            foreach ( (array) $this->instance->getValidationResult() as $key => $val) {
                $return[$this->prefix . $key] = $val;
            }
        }
        return $return;
        
    }
}

?>