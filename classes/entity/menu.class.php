<?php

class Menu extends fvRoot implements iLogger {
    
    protected $currentEntity = '';
	
	function __construct () {
	    $this->currentEntity = __CLASS__;
        parent::__construct(fvSite::$fvConfig->get("entities.{$this->currentEntity}.fields"), 
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.table_name"), 
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.primary_key", "id"),
                            $this->currentEntity);
	}

    function validateName($value) {
        $valid = (strlen($value) > 0);
        $this->setValidationResult("name", $valid, "поле не должно быть пустым");
        return $valid;
    }

    function validateUrl($value) {
        $valid = (strlen($value) > 0);
        $this->setValidationResult("url", $valid, "поле не должно быть пустым");
        return $valid;
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
        $logMessage->edit_link = fvSite::$fvConfig->get('dir_web_root')."menus/?id=".$this->getPk();
        $logMessage->save();
    }  
    
    public function getLogMessage($operation) 
    {
        $message = "Пункт меню был ";
        switch ($operation) {
            case Log::OPERATION_INSERT: $message .= "создан ";break;
            case Log::OPERATION_UPDATE: $message .= "изменен ";break;
            case Log::OPERATION_DELETE: $message .= "удален ";break;
            case Log::OPERATION_ERROR: $message = "Произошла ошибка при операции с записью ";break;
        }
        $user = fvSite::$fvSession->getUser();
        $message .= "в ".date("Y-m-d H:i:s").". Менеджер [".$user->getPk()."] " . $user->getLogin() . " (" . $user->getFullName() . ")";
        return $message;    
    }
    
    /**
    * Получить тип меню
    * @author Nesterenko Nikita
    * @since 2011/07/12
    * @return string
    */
    public function getTypeMenu()
    {
        $typeName = $this->getManager()->getTypeMenu($this->type_id);
        return is_array($typeName) ? "Не определено" : $typeName;
    }
    /**
    * Получить урд
    * @author Nesterenko Nikita
    * @since 2011/07/12
    * @return string
    */
    public function getURL()
    {
        return $this->url;
    }
    /**
    * Открывать в новом окне
    * @author Nesterenko Nikita
    * @since 2011/07/12
    * @return bool
    */    
    public function isTarget()
    {
        return (bool)$this->is_target;
    }
    
    /**
    * Получить дерево меню
    * @author Nesterenko Nikita
    * @since 2011/07/12
    * @return array
    */
    public function getMenuTree($parent_id = false, &$out = array(), $offset = null, $type_id = false)
    {

        $where = array();
        if($parent_id) 
            $where[] = "parent_id='{$parent_id}'";
        else 
            $where[] = "( parent_id is NULL OR parent_id = 0 )";
        if(!$this->isNew())
            $where[] = "id != '{$this->getPk()}'";
        if( $type_id )
            $where[] = "type_id = " .$type_id;
        $where = count($where) ? implode(" AND ", $where) : "";
        $List = $this->getManager()->getAll($where);
        foreach($List as $item)
        {
            $out[$item->getPk()] = $offset . $item->name;
            if( $item->hasChild() )
                 $this->getMenuTree( $item->getPk(), $out , "&nbsp;&nbsp;&nbsp;" . $offset, $type_id);
        }
        return $out;        
    }
    /**
    * Проверить есть ли у пункта меню дети
    * @author Nesterenko Nikita
    * @since 2011/07/13
    * @return bool
    */    
    public function hasChild()
    {
        return (bool)$this->getManager()->getCount("parent_id='{$this->getPk()}'");
    }
    public function getChild()
    {
        return MenuManager::getInstance()->getAll("parent_id={$this->getPk()}");
    }
    /**
    * Получить вес
    * @author Nesterenko Nikita
    * @since 2011/07/12
    * @return int
    */
    public function getWieght()
    {
        return $this->weight;
    }
    
    public function getName()
    {
        return $this->name;
    }
    public function getArrayURL($withLang=false)
    {
        $array = explode(',',$this->url);
        $out = array();
        if ($withLang) {
            foreach ($array as $el) {            
                $out[] = '/' . $el;
                if ($el=='/')
                    $out[] = '/';
            }
        } else $out = $array;
        return $out;
        
    }
    public function isActive()
    {                            
        return $this->getURL(true,false) == $_SERVER['REQUEST_URI']
               || $this->getURL(true) == $_SERVER['REQUEST_URI']
               || in_array($_SERVER['REQUEST_URI'],$this->getArrayURL(false))
               || in_array($_SERVER['REQUEST_URI'],$this->getArrayURL(true));
    }
    public function getSubDomain()
    {
        $url = parse_url($this->getURL());
        list($subd) = explode('.',$url['host']);
        return $subd;
    }
    public function getURLWithoutProtocol()
    {
        return preg_replace("/(((http)|(https))\:\/\/)?(.*)/","\\5",$this->getURL());
    }
    public function isNonActive()
    {
        return (bool)$this->is_non_active;
    }
    
    public function isShow()
    {
        return (bool)$this->is_show;
    }
}

?>
