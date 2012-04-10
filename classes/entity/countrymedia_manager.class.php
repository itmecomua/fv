<?php

require_once (fvSite::$fvConfig->get("path.entity") . 'countrymedia.class.php') ;

/**
* Менеджер сущностей медиа страны
* 
*/
class CountryMediaManager extends DictionaryManager 
{
    const MEDIATYPE_ICON = 1;
    const MEDIATYPE_FLAG = 2;
    
    protected $_listMediaType = array(
        self::MEDIATYPE_FLAG => "Флаг",
        self::MEDIATYPE_ICON => "Иконка"
    );
    
    protected function __construct () 
    {
        $objectClassName = substr(__CLASS__, 0, -7);        
        $this->rootObj = new $objectClassName();
        $this->_objectClassName = $objectClassName;
        $this->_className = __CLASS__;   
    }
    
    static function getInstance()
    {
        static $instance; 
        if (!isset($instance))
            $instance = new self();
        return $instance;
    }
    
    /**
    * Выполнить массовое сохранение фотографий
    * 
    * @param array $images
    * @param Country $country
    * 
    * @exception EUserMessageError 
    * 
    * @return bool результат выполнения операции
    */
    public function saveMassPhoto($images = array(), Country $country)
    {
        
        foreach($images as $id => $update)
        {
            $update['country_id'] = $country->getPk();
            $photo = $this->getByPk($id, true);
            if ($photo->isNew()) 
            {
                $photo->set("type_id",self::MEDIATYPE_ICON);
            }
            else
            {
                $photo->set("type_id",$update["type_id"]);
            }
            
            $photo->addField('oldImage','string', $photo->image);
            $photo->updateFromRequest($update);            
            
            if( !$photo->save() )
                throw new EUserMessageError("Ошибка при сохранении изображений");                                                
        }
    }
    /**
    * Получить список меди типов
    * @param int $typeId тип медиа
    * 
    * @return array список
    */
    public function getListMediaType($typeId=null)
    {
        return is_null($typeId) ? $this->_listMediaType : $this->_listMediaType[$typeId];
    }
    /**
    * Получить путь к папке временных файлов изображений
    *
    *  @return string путь
    */
    public function getTempImageFolder()
    {
        return fvSite::$fvConfig->get('path.upload.temp_image');
    }
    /**
    * Очистить метку заглавное изображение по стране
    * 
    * @param Country $country страна
    * @return bool результат выполнения операции
    */
    public function clearIsMain(Country $country)
    {
        $sql = "update {$this->getTableName()} set is_main=0 where is_main<>0 and country_id={$country->getPk()}";
        $res = fvSite::$DB->query($sql); 
        return !DB::isError($res);
    }    
    

}
