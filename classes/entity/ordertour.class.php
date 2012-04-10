<?php
require_once (fvSite::$fvConfig->get("path.entity") . 'dictionary_manager.class.php') ;

class OrderTour extends Dictionary
{
    
    function __construct () 
    {
        $this->currentEntity = __CLASS__;
        parent::__construct(fvSite::$fvConfig->get("entities.{$this->currentEntity}.fields"),
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.table_name"),
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.primary_key", "id"));
    }
    public function save($isLogging=true)
    {
        if ($this->isNew()) {
            $this->doSendEmail();
        }
        return parent::save($isLogging);
    }
    public function validateEmail($value)
    {
        if (!$this->doValidateEmpty($value)) {
            $msg = "Это  поле обязательное";
        } else if (!$this->doValidateEmail($value)) {
            $msg = "Неверный формат. Введите email в формате test@test.com";
        } else {
            $valid = true;
        }
        $this->setValidationResult("email",$valid,$msg);
        return $valid;
    }
    public function validateName($value)
    {
        $valid = $this->doValidateEmpty($value);
        $this->setValidationResult("name",$valid,"Это поле обязательное");
        return $valid;
    }
    public function validatePhone($value)
    {
        $valid = $this->doValidateEmpty($value);
        $this->setValidationResult("phone",$valid,"Это поле обязательное");
        return $valid;
    }
    public function getDictionaryName()
    {
        return 'Заказ тура';
    }
    public function getStateName()
    {
        $listState = $this->getManager()->getListState();
        return $listState[$this->state];
    }
    public function doSendEmail()
    {
        $mail = new Mail();
        $data = array();
        $data []= "Получен новый заказ тура [" . date("Y-m-d H:i") . "]";
        $data []= "Имя: {$this->name}";
        $data []= "Email: {$this->email}";
        if ($this->phone)
            $data []= "Телефон: {$this->phone}";        
        $data = implode("<br />",$data);
        $emails = (array)fvSite::$fvConfig->get("email.order");
        foreach($emails as $m)
            $mail->SendMail($m,"Заказ",$data);        
    }
    
}    