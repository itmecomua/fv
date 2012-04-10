<?php
/**
* Сущность медиа для тура                                                            
* 
*/
class TourMedia extends Dictionary
{
    
    function __construct () 
    {
        $this->currentEntity = __CLASS__;
        parent::__construct(fvSite::$fvConfig->get("entities.{$this->currentEntity}.fields"),
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.table_name"),
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.primary_key", "id"));
    }
    // validation {{ 
    public function validateFilename($value)
    {
        $valid = $this->isNew() || strlen($this->image) > 0;
        $this->setValidationResult("filename",$valid,"Изображение обязательно для создания записи");        
        return $valid;
    }
   
    /**
    * Получить название справочника
    * 
    * @return string название справочника
    */
    public function getDictionaryName()
    {
        return 'Медиа тура';
    }  
    
    
    /**
    * Получить путь к изображению
    * @param bool $isWeb веб путь
    * @param fvUploaded::const $prefix тип медиа
    * 
    * @return string путь к изображению
    */
    public function getImageSrc($isWeb = true, $prefix = false)
    {
        $web = $isWeb ? "_web" : "";              
        $path = fvSite::$fvConfig->get('path.upload.mediatour'.$web);
        $prefix = !$prefix ? fvUploaded::IMAGE_TYPE_THUMB : fvUploaded::getSelfConst($prefix);
        if(false == $isWeb) return $this->getImageFolder($isWeb) . $prefix."_".$this->image;
        else
        {   
            $real_path = $this->getImageFolder(false) . $prefix."_".$this->image;
            if(file_exists($real_path)) return $this->getImageFolder($isWeb) . $prefix."_".$this->image;
            else return "/img/no_photo_.png";
        }
    }
    
    /**
    * Выполнить сохранение с сохраенением нового изображения
    * @param bool $log логировать операцию
    * 
    * @return bool результат выполнения операции
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
    * 
    * @return bool результат выполнения операции
    */    
    public function delete()
    {
        return $this->deleteImage() ? parent::delete() : false;
    }
    /**
    * Выполнить сохранение одного изображения
    * 
    * @return bool результат выполнения операции
    */    
    public function saveImage()
    {        
        $tmpDir = TourMediaManager::getInstance()->getTempImageFolder();
        $tempImage = $tmpDir . $this->image;
        $destPath = $this->getImageFolder();        
        if(file_exists( $tempImage ))
        {
            $arrType = array(fvUploaded::IMAGE_TYPE_THUMB,
                             fvUploaded::IMAGE_TYPE_SMALL,
                             fvUploaded::IMAGE_TYPE_LARGE,
                             fvUploaded::IMAGE_TYPE_NORMAL);
            foreach ($arrType as $type) 
            {
                $file = "{$destPath}{$type}_{$this->image}";
                fvMediaLib::createThumbnail($tempImage, $file, array("type" => $type, "resize_type" => fvMediaLib::THUMB_WIDTH ));    
            }                        
            unlink($tempImage);
            return true;
            
        } else {
            return false;
        }
        
    }
    
    /**
    * Выполнить удаление одного изображения
    * 
    * @return bool результат выполнения операции
    */    
    public function deleteImage()
    {
        if(!$this->hasField('oldImage'))
        {
            return true;   
        } 
        
        $destPath = $this->getImageFolder();
        $arrType = array(fvUploaded::IMAGE_TYPE_THUMB,
                             fvUploaded::IMAGE_TYPE_SMALL,
                             fvUploaded::IMAGE_TYPE_LARGE,
                             fvUploaded::IMAGE_TYPE_NORMAL);
        foreach ($arrType as $type) 
        {
            $file = "{$destPath}{$type}_{$this->oldImage}";
            if (file_exists($file)) 
            {
                unlink($file);
            }
        }                     
        return true;
    }
    /**
    * Получить путь к изображению
    * @param true $web веб путь?
    * 
    * @return string путь
    */    
    public function getImageFolder($web = false)
    {
        $web = $web ? "_web" : "";        
        $destPath = fvSite::$fvConfig->get("path.upload.mediatour{$web}");        
        $folder = $destPath . str_pad($this->tour_id, 8, 0, STR_PAD_LEFT ) ."/";
        if(!is_dir($folder) && !$web)
        {
            mkdir($folder, 0777);
        }            
        $imageFolder = $folder . str_pad($this->getPk(), 8, 0, STR_PAD_LEFT ) ."/";
        if(!is_dir($imageFolder) && !$web)
        {
            mkdir($imageFolder, 0777);
        }
            
        return $imageFolder;
    }
    /**
    * Заглавное изображение
    * 
    * @return bool Да/Нет
    */
    public function isMain()
    {
        return (bool) $this->is_main;
    } 
}
