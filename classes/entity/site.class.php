<?php

class Site extends fvRoot implements iLogger {
    
    protected $currentEntity = '';
    
    function __construct () {
        $this->currentEntity = __CLASS__;
        parent::__construct(fvSite::$fvConfig->get("entities.{$this->currentEntity}.fields"), 
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.table_name"), 
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.primary_key", "id"));
    }
    
    function validateName($value) {
        $valid = (strlen($value) > 0);
        $this->setValidationResult("name", $valid);
        return $valid;
    }
    
    function validateUrl($value) {
        
        $valid = (preg_match("/^[a-z_\-\.]+$/i", $value) > 0);
        $this->setValidationResult("url", $valid);
        
        return $valid;
    }

    public function validateIp($value) {
        $valid = (preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/i", $value) > 0);
        
        $this->setValidationResult('ip', $valid);
        return $valid;
    }
        
    public function getLogMessage($operation) {
        $message = "Сайт был ";
        switch ($operation) {
            case Log::OPERATION_INSERT: $message .= "создан ";break;
            case Log::OPERATION_UPDATE: $message .= "изменен ";break;
            case Log::OPERATION_DELETE: $message .= "удален ";break;
            case Log::OPERATION_ERROR: $message = "Произошла ошибка при операции с записью ";break;
        }
        $user = fvSite::$fvSession->getUser();
        $message .= "в ".date("Y-m-d H:i:s").". Пользователь [".$user->getPk()."] " . $user->getLogin() . " (" . $user->getFullName() . ")";
        return $message;    
    }
    
    public function getLogName() {
        return $this->name;    
    }
    
    public function putToLog($operation) {
        $logMessage = new Log();
        $logMessage->operation = $operation;
        $logMessage->object_type = __CLASS__;
        $logMessage->object_name = $this->getLogName();
        $logMessage->object_id = $this->getPk();
        $logMessage->manager_id = (fvSite::$fvSession->getUser())?fvSite::$fvSession->getUser()->getPk():-1;
        $logMessage->message = $this->getLogMessage($operation);
        $logMessage->edit_link = fvSite::$fvConfig->get('dir_web_root')."sites/edit/?id=".$this->getPk();
        $logMessage->save();
    }
}

?>
