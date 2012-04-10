<?php
  //news.class.php
  class News extends fvRoot implements iLogger 
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
        $valid = ( strlen($value) > 0);
        $this->setValidationResult("name", $valid, "Имя не должно быть пустым");
        return $valid;
    }
    
    function validateUrl($value) 
    {
        $valid = ( preg_match( "/^[a-z0-9\_]{1,}$/", $value ) > 0);
        $this->setValidationResult("url", $valid,"URL не должен быть пустым и должен состоять из знаков a-z, 0-9 b '_'.");
        
        $obj = call_user_func(array("{$this->currentEntity}Manager", "getInstance"));        
        $valid = $valid && (($count = $obj->getCount('url = ?', array($value))) == 0);
        
        if ($count > 0) $this->setValidationResult("url", $valid, 'URL должен быть уникальным');
        return $valid;            
    }
    
    function validateHeading($value) 
    {
        $valid = ( strlen($value) > 0);
        $this->setValidationResult("heading", $valid, "Превью не должно быть пустым");
        return $valid;
    }
    
    function validateText($value) 
    {
        $valid = ( strlen($value) > 0);
        $this->setValidationResult("text", $valid, "Текст новости не должен быть пустым");
        return $valid;
    }
    
    public function getLogMessage($operation) 
    {
        $message = "Тег был ";
        switch ($operation) {
            case Log::OPERATION_INSERT: $message .= "создан ";break;
            case Log::OPERATION_UPDATE: $message .= "изменен ";break;
            case Log::OPERATION_DELETE: $message .= "удален ";break;
            case Log::OPERATION_ERROR: $message = "Произошла ошибка при операции с записью ";break;
        }
        $user = fvSite::$fvSession->getUser();
        if ( $user instanceof User )           
            $message .= "в ".date("Y-m-d H:i:s").". Менеджер [".$user->getPk()."] " . $user->getLogin() . " (" . $user->getFullName() . ")";
        else
            $message .= "автоинкримент просмотров.";
        return $message;    
    }
    
    public function getLogName() 
    {
        return $this->name;    
    }
    
    public function putToLog($operation) 
    {
        $logMessage = new Log();
        $logMessage->operation = $operation;
        $logMessage->object_type = __CLASS__;
        $logMessage->object_name = $this->getLogName();
        $logMessage->object_id = $this->getPk();
        $logMessage->manager_id = (fvSite::$fvSession->getUser())?fvSite::$fvSession->getUser()->getPk():-1;
        $logMessage->message = $this->getLogMessage($operation);
        $logMessage->edit_link = fvSite::$fvConfig->get('dir_web_root')."news/?id=".$this->getPk();
        $logMessage->save();
    }  
    
    public function getIUrl( $anchor = false )
    {
        $string  = fvSite::$fvConfig->get( 'dir_web_root' );
        $string .= "news/";
        $string .= $this->url."/";
        $string .= ( $anchor ) ? "#{$anchor}" : "";
        
        return $string;   
    }
    
    public function incShows()
    {
        ++$this->shows;           
        return $this->save(); 
    }
    public function hasMeta()
    {
        return !$this->getMeta()->isNew();
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
    public function getHeading()
    {
        return $this->heading;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getText()
    {        
        return $this->text;
    }
    
    
   /**
    * Получить путь к изображению
    * @param  
    * @return string
    */
    public function getImageSrc($isWeb = true, $prefix = false)
    {
        $web = $isWeb ? "_web" : "";              
        $path = fvSite::$fvConfig->get('path.upload.news'.$web);        
        $prefix = !$prefix ? fvUploaded::IMAGE_TYPE_THUMB : fvUploaded::getSelfConst($prefix);
        return $this->getImageFolder($isWeb) . $prefix."_".$this->image;
    }
/*
* Методы для работы с изображением
* 
*/    
    
    /**
    * Выполнить сохранение с сохраенением нового изображения
    * @return bool
    */    
    public function save($isLogging=true)
    {        
        $this->set("meta_id",$this->getMeta()->getPk());        
        $save = parent::save($isLogging);
        
        if( $this->hasField('oldImage') )
        {
            if($this->image != $this->oldImage)
            {
                $this->saveImage();
                $this->deleteImage();
            }
            $this->removeField('oldImage');
        }
        return $save;
    }
    
    /**
    * Выполнить удаление с удалением изображения
    * @return bool
    */    
    public function delete()
    {
        $this->deleteImage();
        return parent::delete();
    }
    
    /**
    * Выполнить сохранение одного изображения
    * @return bool
    */    
    public function saveImage()
    {       
        $tmpDir     = fvSite::$fvConfig->get('path.upload.temp_image');       
        $tempImage  = $tmpDir . $this->image;
        $destPath   = $this->getImageFolder();
        
        
        

        if(file_exists( $tempImage ))
        {
            
            
        echo "tmpDir"    . $tmpDir . "<br>";
        echo "tempImage" . $tempImage . "<br>" ;
        echo "destPath"  . $destPath . "<br>" ;
            
            
            $file = $destPath . fvUploaded::IMAGE_TYPE_THUMB."_".$this->image;
            fvMediaLib::createThumbnail($tempImage, $file, array(
                "type"          => fvUploaded::IMAGE_TYPE_THUMB, 
                "resize_type"   => fvMediaLib::THUMB_WIDTH )
                );

            $file = $destPath . fvUploaded::IMAGE_TYPE_SMALL."_".$this->image;
            fvMediaLib::createThumbnail($tempImage, $file, array(
                "type"          => fvUploaded::IMAGE_TYPE_SMALL, 
                "resize_type"   => fvMediaLib::THUMB_WIDTH )
                );

            $file = $destPath . fvUploaded::IMAGE_TYPE_SMALLONE."_".$this->image;
            fvMediaLib::createThumbnail($tempImage, $file, array(
                "type"          => fvUploaded::IMAGE_TYPE_SMALL, 
                "resize_type"   => fvMediaLib::THUMB_WIDTH )
                );

            $file = $destPath . fvUploaded::IMAGE_TYPE_SMALLTWO."_".$this->image;
            fvMediaLib::createThumbnail($tempImage, $file, array(
                "type"          => fvUploaded::IMAGE_TYPE_SMALL, 
                "resize_type"   => fvMediaLib::THUMB_WIDTH )
                );

            $file = $destPath . fvUploaded::IMAGE_TYPE_LARGE."_".$this->image;
            fvMediaLib::createThumbnail($tempImage, $file, array(
                "type"          => fvUploaded::IMAGE_TYPE_LARGE, 
                "resize_type"   => fvMediaLib::THUMB_WIDTH )
            );
            
            $file = $destPath . fvUploaded::IMAGE_TYPE_NORMAL."_".$this->image;
            fvMediaLib::createThumbnail($tempImage, $file, array(
                "type"          => fvUploaded::IMAGE_TYPE_NORMAL, 
                "resize_type"   => fvMediaLib::THUMB_WIDTH )
                );

            unlink($tempImage);
        }   
    }
    
    /**
    * Выполнить удаление одного изображения
    * @return bool
    */    
    public function deleteImage()
    {
        if(!$this->hasField('oldImage'))
            return;
            
        $destPath = $this->getImageFolder();
        $file = $destPath . fvUploaded::IMAGE_TYPE_THUMB."_".$this->oldImage;
        if(file_exists($file)){ unlink($file); }
            
        $file = $destPath . fvUploaded::IMAGE_TYPE_SMALL."_".$this->oldImage;
        if(file_exists($file)){ unlink($file); }
            
        $file = $destPath . fvUploaded::IMAGE_TYPE_SMALLONE."_".$this->oldImage;
        if(file_exists($file)){ unlink($file); }
            
        $file = $destPath . fvUploaded::IMAGE_TYPE_SMALLTWO."_".$this->oldImage;
        if(file_exists($file)){ unlink($file); }
            
        $file = $destPath . fvUploaded::IMAGE_TYPE_LARGE."_".$this->oldImage;
        if(file_exists($file)){ unlink($file); }
            
        $file = $destPath . fvUploaded::IMAGE_TYPE_NORMAL."_".$this->oldImage;
        if(file_exists($file)){ unlink($file); }
            
    }
    
    /**
    * Получить путь к директории где находится изображение
    * @return string
    */    
    public function getImageFolder($web = false)
    {
        $web = $web ? "_web" : "";
        $destPath = fvSite::$fvConfig->get('path.upload.news'.$web);
        $imageFolder = $destPath . str_pad($this->getPk(), 8, 0, STR_PAD_LEFT ) ."/";
        if(!is_dir($imageFolder) && !$web)
        {
            mkdir($imageFolder, 0777);
        }
        return $imageFolder;
    }    
    
}
