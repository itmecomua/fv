<?php

class Page extends fvRoot implements iLogger {
    
    protected $currentEntity = '';
    
    function __construct () {
        $this->currentEntity = __CLASS__;
        
        parent::__construct(fvSite::$fvConfig->get("entities.{$this->currentEntity}.fields"), 
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.table_name"), 
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.primary_key", "id"));
    }
    
    function validatePage_name($value) {
        $valid = (strlen($value) > 0) && (strtolower($value) !== "default");
        $this->setValidationResult('page_name', $valid);
        return $valid;
        
    }
    
    function validatePage_url($value) {
        $valid = (strlen($value) > 0);
        $this->setValidationResult('page_url', $valid);
        return $valid;
        
    }
    public function save($isLogging=true)
    {        
        if ($this->hasField("oldImage") && $this->image != $this->oldImage) {
            $this->createImage();
        }
        $this->getMeta()->save();
        $this->set("meta_id",$this->getMeta()->getPk());        
        
        return parent::save($isLogging);
    }
    public function delete() 
    {                
        if ($this->image) @unlink( $this->getImageRealPath() );
        $childPages = PageManager::getInstance()->getByPageParentId($this->getPk());        
        foreach ($childPages as $childPage) {
            $childPage->page_parent_id = 0;
            $childPage->save();
        }        
        return parent::delete();
    }
    
    function getPageContent() 
    {
        try {
            if (!$dom = @DOMDocument::loadXML($this->get('page_content'))) {
                $dom = new DOMDocument("1.0", fvSite::$fvConfig->get("encoding"));
                
                $page = $dom->createElement("page");
                $page->setAttribute("id", md5(microtime()));
                
                $page = $dom->appendChild($page);
                
                return $dom->saveXML();
            } else return $this->page_content;
        }
        catch (Exception $e) {
            var_dump($e->getMessage());
        }
    }
    
    public function getLogMessage($operation) {
        $message = "Страница была ";
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
        return $this->page_name;    
    }
    
    public function putToLog($operation) {
        $logMessage = new Log();
        $logMessage->operation = $operation;
        $logMessage->object_type = __CLASS__;
        $logMessage->object_name = $this->getLogName();
        $logMessage->object_id = $this->getPk();
        $logMessage->manager_id = (fvSite::$fvSession->getUser())?fvSite::$fvSession->getUser()->getPk():-1;
        $logMessage->message = $this->getLogMessage($operation);
        $logMessage->edit_link = fvSite::$fvConfig->get('dir_web_root')."pages/?id=".$this->getPk();
        $logMessage->save();
    }    
    public function getMeta()
    {
        $fieldName = "_metaobject";
        if (!$this->hasField($fieldName)) {
            if ($this->meta_id > 0) {
                $meta = MetaManager::getInstance()->getByPk($this->meta_id);
            }
            if (!MetaManager::getInstance()->isRootInstance($meta))
                $meta = MetaManager::getInstance()->cloneRootInstance();
            $this->addField($fieldName,'object',$meta);        
        }
        return $this->$fieldName;
    }
    public function setMeta($meta)
    {
        $fieldName = "_metaobject";
        if ($meta->save()) {
            $this->addField($fieldName,'object',$meta);
            return true;
        }
    }
    public function getImagePath()
    {
        return fvSite::$fvConfig->get( "path.upload.media_web" ).$this->image;    
    }
    
    public function getImageRealPath()
    {
        return fvSite::$fvConfig->get( "path.upload.media" ).$this->image;          
    }
    
    public function createImage()
    {
                   
        $tmpPath = fvSite::$fvConfig->get( "path.upload.temp_image" ).$this->image;
        return rename( $tmpPath, $this->getImageRealPath() );
    }    
    
}

?>
