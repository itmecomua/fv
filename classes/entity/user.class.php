<?php

class User extends fvUser implements iLogger {

    protected $currentEntity = '';

    function __construct () {
        $this->currentEntity = __CLASS__;
        parent::__construct(fvSite::$fvConfig->get("entities.{$this->currentEntity}.fields"),
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.table_name"),
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.primary_key", "id"));
    }

    public function validateLogin($value)
    {
        //$valid = (preg_match("/^[a-z_\s0-9]{4,}$/i", $value) > 0);
        $valid = (preg_match("/^[a-z0-9_\-\.]+@[a-z_\-\.]+\.[a-z]{2,3}$/i", $value) > 0);//лоинг он же email

        $this->setValidationResult("login", $valid);

        $valid = $valid && (($count = UserManager::getInstance()->getCount('login = ?', array($value))) == 0);
        if ($count > 0)
        {
            $this->setValidationResult("login", $valid, 'Логин должен быть уникальным');
        }

        return $valid;
    }

    public function validatePassword($value) {
        $valid = (preg_match("/^[a-z_\s0-9]{4,}$/i", $value) > 0);
        $this->setValidationResult("password", $valid);

        $m = fvRequest::getInstance()->getRequestParameter("m");
        $test = fvRequest::getInstance()->getRequestParameter("test");
        
        $confirmPassword = (!empty($m['password1']))?$m['password1']:fvParams::getInstance()->getParameter("users/passwordConfirmation");
        
        $valid = $valid && ($confirmPassword == $value);
        $this->setValidationResult("password1", ($confirmPassword == $value), "Пароль и подтверждение не совпадают");

        return $valid;
    }

    public function validateEmail($value)
    {
        $valid = (preg_match("/^[a-z0-9_\-\.]+@[a-z_\-\.]+\.[a-z]{2,3}$/i", $value) > 0);

        $this->setValidationResult('email', $valid);

        return $valid;
    }

    function check_acl ($acl_name, $action = 'index')
    {
        if ($this->isRoot()) return true;

        if (!is_array($acl_name)) $acl_name = array($acl_name);

        if (is_array($acl_name[$action])) $acl_check = $acl_name[$action];
        else $acl_check = $acl_name;

        return (count(array_intersect($this->get("permitions"), $acl_check)) > 0);
    }

    function isRoot() {
        return $this->get("is_root");
    }

    function getLogin() {
        return $this->login;
    }

    function getFullName() {
        return $this->full_name;
    }

    public function getLogMessage($operation) {
        $message = "Пользователь был ";
        switch ($operation) {
            case Log::OPERATION_INSERT: $message .= "создан ";break;
            case Log::OPERATION_UPDATE: $message .= "изменен ";break;
            case Log::OPERATION_DELETE: $message .= "удален ";break;
            case Log::OPERATION_ERROR: $message = "Произошла ошибка при операции с записью ";break;
        }
        $user = fvSite::$fvSession->getUser();
        $message .= "в ".date("Y-m-d H:i:s")."." . (is_object($user)?" Пользователь [".$user->getPk()."] " . $user->getLogin() . " (" . $user->getFullName() . ")":'');
        return $message;
    }

    public function getLogName() {
        return $this->full_name;
    }

    public function putToLog($operation) {
        $logMessage = new Log();
        $logMessage->operation = $operation;
        $logMessage->object_type = __CLASS__;
        $logMessage->object_name = $this->getLogName();
        $logMessage->object_id = $this->getPk();
        $logMessage->manager_id = (fvSite::$fvSession->getUser())?fvSite::$fvSession->getUser()->getPk():-1;
        $logMessage->message = $this->getLogMessage($operation);
        $logMessage->edit_link = fvSite::$fvConfig->get('dir_web_root')."users/edit/?id=".$this->getPk();
        $logMessage->save();
    }
    
    public function getFormPk(){
        $arr = FormManager::getInstance()->getAll("userPk = ".$this->getPk());
        if (!$arr[0])
            return false;
        return $arr[0]->getPk();
    }
}

?>
