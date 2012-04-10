<?php

class StaticPages extends fvRoot implements iLogger {
    
    protected $currentEntity = '';
	
	function __construct () {
	    $this->currentEntity = __CLASS__;
        parent::__construct(fvSite::$fvConfig->get("entities.{$this->currentEntity}.fields"), 
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.table_name"), 
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.primary_key", "id"));
	}
	
    function validateTech_url($value) {
        $valid = (strlen($value) > 0);
        
        $valid = $valid && (StaticPagesManager::getInstance()->getCount("tech_url = ?", array($value)) == 0);
        
        $this->setValidationResult("tech_url", $valid);
        return $valid;
    }

    function validateName($value) {
        $valid = (strlen($value) > 0);
        $this->setValidationResult("name_ru", $valid);
        return $valid;
    }

    function validateTitle($value) {
        $valid = (strlen($value) > 0);
        $this->setValidationResult("title_ru", $valid);
        return $valid;
    }
    
    public function getLogMessage($operation) {
        $message = "Статическая страница была ";
        switch ($operation) {
            case Log::OPERATION_INSERT: $message .= "создана ";break;
            case Log::OPERATION_UPDATE: $message .= "изменена ";break;
            case Log::OPERATION_DELETE: $message .= "удалена ";break;
            case Log::OPERATION_ERROR: $message = "Произошла ошибка при операции с записью ";break;
        }
        $user = fvSite::$fvSession->getUser();
        $message .= "в ".date("Y-m-d H:i:s").". Менеджер [".$user->getPk()."] " . $user->getLogin() . " (" . $user->getFullName() . ")";
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
        $logMessage->edit_link = fvSite::$fvConfig->get('dir_web_root')."staticpages/edit/?id=".$this->getPk();
        $logMessage->save();
    }  
    public function getName()
    {
        return $this->name;
    }
    public function getTechURL()
    {
        return $this->tech_url;
    }
    public function getTitle()
    {
        return $this->title;
    }
    public function getContent()
    {
        return $this->content;
    }
    public function isShowTitle()
    {
        return $this->show_title;
    }
    
    
}

?>
