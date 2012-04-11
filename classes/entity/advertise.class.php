<?php
   /**
   * Центральный рекламный блокы
   */
class Advertise extends fvRoot
{
    protected $currentEntity = ''; 
    
    function __construct () 
    {
        $this->currentEntity = __CLASS__;
        parent::__construct(fvSite::$fvConfig->get("entities.{$this->currentEntity}.fields"),
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.table_name"),
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.primary_key", "id"));
    }
      
    function validateName($value)
    {
        $valid = $this->doValidateEmpty($value);
        $this->setValidationResult('name', $valid, "Поле обязательное");
        return $valid;
    }  
    public function getTypeName()
    {
        return AdvertiseManager::getInstance()->getListType($this->type_id);
    }        
    /**
    * Получить ид типа
    * @return int type_id
    */
    public function getType()
    {
        return $this->type_id;
    }
    /**
    * Получить заголовок
    * @return string
    */
    public function getName()
    {
        return (string) $this->name;
    }      
    /**
    * Получить текст
    * @return string текст
    */
    public function getText()
    {
        return (string) $this->text;
    }            
    /**
    * Получить вес
    * @return int
    */
    public function getWeight()
    {
        return (int) $this->weight;
    } 
    /**
    * Отображать?
    * @return bool
    */
    public function isShow()
    {
        return (bool)$this->is_show;
    }
    /**
    * Получить путь к изображению
    * @param
    * @return string
    */
    public function getImageSrc($isWeb = true, $prefix = false)
    {
        $web = $isWeb ? "_web" : "";
        $path = fvSite::$fvConfig->get('path.upload.advertise'.$web);
        $prefix = !$prefix ? fvUploaded::IMAGE_TYPE_THUMB : fvUploaded::getSelfConst($prefix);
        return $this->getImageFolder($isWeb) . $prefix."_".$this->image;
    }
    /**
    * Выполнить сохранение с сохраенением нового изображения
    * @return bool
    */    
    public function save($log = true)
    {
        $save = parent::save($log);
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
        $tmpDir = fvSite::$fvConfig->get('path.upload.temp_image');
        $tempImage = $tmpDir . $this->image;
        $destPath = $this->getImageFolder();        
        if(file_exists( $tempImage ))
        {
            $file = $destPath . fvUploaded::IMAGE_TYPE_THUMB."_".$this->image;

            fvMediaLib::createThumbnail($tempImage, $file, array("type" => fvUploaded::IMAGE_TYPE_THUMB, "resize_type" => fvMediaLib::THUMB_WIDTH ));
            $file = $destPath . fvUploaded::IMAGE_TYPE_SMALL."_".$this->image;
            fvMediaLib::createThumbnail($tempImage, $file, array("type" => fvUploaded::IMAGE_TYPE_SMALL, "resize_type" => fvMediaLib::THUMB_WIDTH ));
            $file = $destPath . fvUploaded::IMAGE_TYPE_LARGE."_".$this->image;
            fvMediaLib::createThumbnail($tempImage, $file, array("type" => fvUploaded::IMAGE_TYPE_LARGE, "resize_type" => fvMediaLib::THUMB_WIDTH ));
            $file = $destPath . fvUploaded::IMAGE_TYPE_NORMAL."_".$this->image;
            fvMediaLib::createThumbnail($tempImage, $file, array("type" => fvUploaded::IMAGE_TYPE_NORMAL, "resize_type" => fvMediaLib::THUMB_WIDTH ));

            $file = $destPath . fvUploaded::IMAGE_TYPE_CRBCENTER."_".$this->image;
            fvMediaLib::createThumbnail($tempImage, $file, array("type" => fvUploaded::IMAGE_TYPE_CRBCENTER, "resize_type" => fvMediaLib::THUMB_WIDTH ));
            $file = $destPath . fvUploaded::IMAGE_TYPE_CRBRIGHT."_".$this->image;
            fvMediaLib::createThumbnail($tempImage, $file, array("type" => fvUploaded::IMAGE_TYPE_CRBRIGHT, "resize_type" => fvMediaLib::THUMB_WIDTH ));
            $file = $destPath . fvUploaded::IMAGE_TYPE_CRBLEFT."_".$this->image;
            fvMediaLib::createThumbnail($tempImage, $file, array("type" => fvUploaded::IMAGE_TYPE_CRBLEFT, "resize_type" => fvMediaLib::THUMB_WIDTH ));

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
        if(file_exists($file))
            unlink($file);
        $file = $destPath . fvUploaded::IMAGE_TYPE_SMALL."_".$this->oldImage;
        if(file_exists($file))
            unlink($file);
        $file = $destPath . fvUploaded::IMAGE_TYPE_LARGE."_".$this->oldImage;
        if(file_exists($file))
            unlink($file);
        $file = $destPath . fvUploaded::IMAGE_TYPE_NORMAL."_".$this->oldImage;
        if(file_exists($file))
            unlink($file);
        $file = $destPath . fvUploaded::IMAGE_TYPE_CRBCENTER."_".$this->oldImage;
        if(file_exists($file))
            unlink($file);
        $file = $destPath . fvUploaded::IMAGE_TYPE_CRBRIGHT."_".$this->oldImage;
        if(file_exists($file))
            unlink($file);
        $file = $destPath . fvUploaded::IMAGE_TYPE_CRBLEFT."_".$this->oldImage;
        if(file_exists($file))
            unlink($file);

    }
    /**
    * Получить путь где находится изображение
    * @return string
    */    
    public function getImageFolder($web = false)
    {
        $web = $web ? "_web" : "";
        $destPath = fvSite::$fvConfig->get('path.upload.advertise'.$web);        
        $imageFolder = $destPath . str_pad($this->getPk(), 8, 0, STR_PAD_LEFT ) ."/";
        if(!is_dir($imageFolder) && !$web)
            mkdir($imageFolder, 0777);
        return $imageFolder;
    }
    public function getURL()
    {
        return $this->url;
    }
    public function isTargetBlank()
    {
        return (bool)$this->target_blank;
    }
}