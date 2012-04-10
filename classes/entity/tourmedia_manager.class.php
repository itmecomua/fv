<?php

require_once (fvSite::$fvConfig->get("path.entity") . 'tourmedia.class.php') ;

/**
* Менеджер сущностей медиа туров
* 
*/
class TourMediaManager extends DictionaryManager 
{
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
    * @param Country $tour
    * 
    * @exception EUserMessageError 
    * 
    * @return bool результат выполнения операции
    */
    public function saveMassPhoto($images = array(), Tour $tour)
    {
        
        foreach($images as $id => $update)
        {
            $update['tour_id'] = $tour->getPk();
            $photo = $this->getByPk($id, true);
            $photo->addField('oldImage','string', $photo->image);
            $photo->updateFromRequest($update);            
            
            if( !$photo->save() )
                throw new EUserMessageError("Ошибка при сохранении изображений");                                                
        }
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
    * @param Tour $tour страна
    * @return bool результат выполнения операции
    */
    public function clearIsMain(Tour $tour)
    {
        $sql = "update {$this->getTableName()} set is_main=0 where is_main<>0 and tour_id={$tour->getPk()}";
        $res = fvSite::$DB->query($sql); 
        return !DB::isError($res);
    }    
    

}
