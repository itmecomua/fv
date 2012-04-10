<?php
/**
*   PriceOfDay
*/

class PriceOfDay extends fvRoot
{
    protected $currentEntity = ''; 
    
    function __construct () 
    {
        $this->currentEntity = __CLASS__;
        parent::__construct(fvSite::$fvConfig->get("entities.{$this->currentEntity}.fields"),
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.table_name"),
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.primary_key", "id"));
    }
    
/**
*   Validation
*/
    function validateDiscount($value)
    {
        $valid = $this->doValidateEmpty($value);
        $this->setValidationResult('discount', $valid, "Поле обязательное");
        return $valid;
    }      
    
    function validateName($value)
    {
        $valid = $this->doValidateEmpty($value);
        $this->setValidationResult('name', $valid, "Поле обязательное");
        return $valid;
    }
    
    function validateShort_text($value)
    {    
        $valid = $this->doValidateEmpty($value);
        $this->setValidationResult('short_text', $valid, "Поле обязательное");
        /*
        $valid = mb_strlen($value) < 255;        
        $this->setValidationResult('short_text', $valid, "Поле должно быть не длинее 255 символов");
        */
        return $valid;
    }
    
    function validatePriceold($value)
    {
        $valid = $this->doValidateEmpty($value);
        $this->setValidationResult('priceold', $valid, "Поле обязательное");
        return $valid;
    }
    
    function validatePricenew($value)
    {
        $valid = $this->doValidateEmpty($value);
        $this->setValidationResult('pricenew', $valid, "Поле обязательное");
        return $valid;
    }    
    

    function validateImage($value)
    {
        $valid = $this->doValidateEmpty($value);
        $this->setValidationResult('image', $valid, "Поле обязательное");
        return $valid;
    }

    
    function validateUrl($value)
    {
        $valid = $this->doValidateEmpty($value);
        $this->setValidationResult('url', $valid, "Поле обязательное");
        return $valid;
    }
    
/*
*  Методы получения полей
* 
*/
    
    /**
    * Получить скидку
    * @return int
    */
    public function getDiscount()
    {                           
        return (int) $this->discount;
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
    * Получить короткий текст
    * @return string
    */    
    public function getShortText()
    {
        return (string) $this->short_text;
    }
    
    /**
    * Получить старую цену
    * @return string
    */    
    public function getPriceold()
    {
        return (string) $this->priceold;
    }

    /**
    * Получить новую цену
    * @return string
    */    
    public function getPricenew()
    {
        return (string) $this->pricenew;
    }
    
   /**
    * Получить путь к изображению
    * @param  
    * @return string
    */
    public function getImageSrc($isWeb = true, $prefix = false)
    {
        $web = $isWeb ? "_web" : "";              
        $path = fvSite::$fvConfig->get('path.upload.priceofday'.$web);        
        $prefix = !$prefix ? fvUploaded::IMAGE_TYPE_THUMB : fvUploaded::getSelfConst($prefix);
        return $this->getImageFolder($isWeb) . $prefix."_".$this->image;
    }
   
    /**
    * Получить URL
    * @return string URL
    */
    public function getURL()
    {
        return (string) $this->url;
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
    * Открывать в новом окне?
    * @return bool
    */
    public function isTarget()
    {
        return (bool)$this->is_target;
    }
    
    
/*
* Методы для работы с изображением
* 
*/    
    
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
        $tmpDir     = fvSite::$fvConfig->get('path.upload.temp_image');       
        $tempImage  = $tmpDir . $this->image;
        $destPath   = $this->getImageFolder();

        if(file_exists( $tempImage ))
        {
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
        $destPath = fvSite::$fvConfig->get('path.upload.priceofday'.$web);
        $imageFolder = $destPath . str_pad($this->getPk(), 8, 0, STR_PAD_LEFT ) ."/";
        if(!is_dir($imageFolder) && !$web)
        {
            mkdir($imageFolder, 0777);
        }
        return $imageFolder;
    }    
    
}
