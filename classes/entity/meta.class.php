<?php

class Meta extends fvRoot implements iLogger {
    
    protected $currentEntity = '';
    
    function __construct () {
        $this->currentEntity = __CLASS__;
        parent::__construct(fvSite::$fvConfig->get("entities.{$this->currentEntity}.fields"), 
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.table_name"), 
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.primary_key", "id"));
    }

    public function getLogMessage($operation) {
        $message = "Мета был ";
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
        return $this->title;    
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
     public function getTitle()
     {
         return $this->title;
     }
     public function getDescription()
     {
         return $this->description;
     }
     public function getKeywords()
     {
         return $this->keywords;
     }
}

?>
