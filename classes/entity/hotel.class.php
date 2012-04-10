<?php
/**
* Сушность отеля
*/
class Hotel extends Dictionary
{
    
    function __construct () 
    {
        $this->currentEntity = __CLASS__;
        parent::__construct(fvSite::$fvConfig->get("entities.{$this->currentEntity}.fields"),
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.table_name"),
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.primary_key", "id"));
    }
    
    function validateName( $value )
    {
        $valid = $this->doValidateEmpty($value);
        $this->setValidationResult('name', $valid, "Поле обязательное для заполнения");
        return $valid;
    }
   
    function validateName_transcription( $value )
    {
        $valid = $this->doValidateEmpty($value);
        $this->setValidationResult('name_transcription', $valid, "Поле обязательное для заполнения");
        return $valid;
    }
   
    function validateUrl( $value )
    {
        $valid = $this->doValidateEmpty($value);
        if(!$valid)
        {
            $this->setValidationResult('url', $valid, "Поле обязательное для заполнения");
            return $valid;            
        }
        $valid = $this->doValidateUniq($value, 'url');
        $this->setValidationResult('url', $valid, "Поле должно быть уникальным");
        return $valid;
    }
    
    function validateCountry_id( $value )
    {
        $valid = $this->doValidateDict($value, CountryManager::getInstance() );
        $this->setValidationResult('country_id', $valid, "Поле обязательное для заполнения");
        return $valid;
    }
    
    function validateResort_id( $value )
    {
        $valid = $this->doValidateDict($value, ResortManager::getInstance() );
        $this->setValidationResult('resort_id', $valid, "Поле обязательное для заполнения");
        return $valid;
    }
   
    function validateHotel_type_id( $value )
    {
        return true; // согласно таски от 19,11,2011 поле необязательное
        
        $valid = $this->doValidateDict($value, HotelTypeManager::getInstance() );
        $this->setValidationResult('hotel_type_id', $valid, "Поле обязательное для заполнения");
        return $valid;
    }
    
    public function getDictionaryName()
    {
        return 'Отель';
    }  
    
    public function getURL()
    {
        return $this->url;
    }
  
    public function getCountry()
    {
        $this->_getDictFieldBy('country', CountryManager::getInstance(), $this->country_id);
        return CountryManager::getInstance()->isRootInstance( $this->country ) ? $this->country : CountryManager::getInstance()->cloneRootInstance();
    }
   
    public function getResort()
    {        
        $this->_getDictFieldBy('resort', ResortManager::getInstance(), $this->resort_id);
        return ResortManager::getInstance()->isRootInstance( $this->resort ) ? $this->resort : ResortManager::getInstance()->cloneRootInstance();
    }
    
    public function getNameTranscription()
    {
        $res = $this->get("name_transcription");
        
        return ($res) ? $res : $this->getName();
    }       
    /**
    * Получить сущность тип отеля
    * 
    * @return HotelType
    */
    public function getHotelType()
    {
        $this->_getDictFieldBy('hoteltype', HotelTypeManager::getInstance(), $this->hotel_type_id);        
        return HotelTypeManager::getInstance()->isRootInstance( $this->hoteltype ) ? $this->hoteltype : HotelTypeManager::getInstance()->cloneRootInstance();
    }       
    
    public function getCntView()
    {
         return  $this->cnt_view;
    }   
    /**
    * Получить полный текст, описание
    * 
    * @return string
    */
    public function getFullText()
    {
       return $this->full_text; 
    }
    /**
    * Получить короткий текст
    * 
    * @return string
    */
    public function getShortText()
    {
       return $this->short_text; 
    }
    /**
    * Получить все фото отеля
    * 
    * @return array
    */
    public function getPhoto()
    {
        return (array)HotelMediaManager::getInstance()->getAll("hotel_id='{$this->getPk()}'", "weight asc");
    }
    
    public function getPhotoGallery()
    {
        return (array)HotelMediaManager::getInstance()->getAll("hotel_id='{$this->getPk()}'", "weight asc");
    }
    
    /**
    * Получить URL просмотра полной информации об отеле
    * @author Korshenko Alexey
    * @since  2011/11/23
    * 
    * @return string
    */ 
    public function getViewURL()
    {
        return "/hotels/view/{$this->getURL()}";
    }
    
    /**
    * Получить main фото отеля
    * 
    */
    public function getMainPhoto()
    {
        if(false == $this->hasField("mainPhoto"))
        {
            $list = (array)HotelMediaManager::getInstance()->getAll("hotel_id='{$this->getPk()}'", "is_main desc, weight asc","0,1");
            $ex = null;
            if(count($list) > 0) $ex = current($list);
            if(!HotelMediaManager::getInstance()->isRootInstance($ex))         
                $ex = HotelMediaManager::getInstance()->cloneRootInstance();
                
            $this->addField("mainPhoto","object",$ex);        
        }
        return $this->mainPhoto;
    }
    /**
    * Увеличить счётчик просмотров
    * @author Dmitriy Khoroshilov
    * @since  2011/11/30
    * 
    */ 
    public function setCountView()
    {
        $sql = "update ".HotelManager::getInstance()->getTableName()." set cnt_view = cnt_view + 1 where id = ".$this->getPk();        
        $res = @fvSite::$DB->query($sql);
    }


}
