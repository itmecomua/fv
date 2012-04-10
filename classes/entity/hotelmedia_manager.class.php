<?php

require_once (fvSite::$fvConfig->get("path.entity") . 'hotelmedia.class.php') ;

/**
* Менеджер сущностей медиа отеля
* 
*/
class HotelMediaManager extends DictionaryManager 
{
    const MEDIATYPE_DEFAULT = 1;    
    
    protected $_listMediaType = array( );
    
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
            $instance = new HotelMediaManager();
        return $instance;
    }
    
    /**
    * Выполнить массовое сохранение фотографий
    * 
    * @param array $images
    * @param Hotel $hotel
    * 
    * @exception EUserMessageError 
    * 
    * @return bool результат выполнения операции
    */
    public function saveMassPhoto($images = array(), Hotel $hotel)
    {
        
        foreach($images as $id => $update)
        {
            $update['hotel_id'] = $hotel->getPk();
            $photo = $this->getByPk($id, true);
            if ($photo->isNew()) 
            {
                $photo->set("type_id",self::MEDIATYPE_DEFAULT);
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
    * @param Hotel $hotel отель
    * @return bool результат выполнения операции
    */
    public function clearIsMain(Hotel $hotel)
    {
        $sql = "update {$this->getTableName()} set is_main=0 where is_main<>0 and hotel_id={$hotel->getPk()}";
        $res = fvSite::$DB->query($sql); 
        return !DB::isError($res);
    }    
    

}
