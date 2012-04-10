<?php
require_once (fvSite::$fvConfig->get("path.entity") . 'dictionary_manager.class.php') ;

class Country extends Dictionary
{
    private $prefix_path;
    function __construct () 
    {
        $this->prefix_path = "/countries/view/";
        $this->currentEntity = __CLASS__;
        parent::__construct(fvSite::$fvConfig->get("entities.{$this->currentEntity}.fields"),
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.table_name"),
                            fvSite::$fvConfig->get("entities.{$this->currentEntity}.primary_key", "id"));
    }
    
    public function getDictionaryName()
    {
        return 'Страна';
    }
    
    public function isShowPromo()
    {
        return (bool)$this->is_show_promo;
    }
    
     public function getShortText()
    {
        return (string)$this->short_text;
    }
    
    public function getFullText()
    {
        return (string)$this->full_text;
    }
    
    public function getDocuments()
    {
        return (string)$this->documents;
    }
    
    public function getCntView()
    {
         return  (int)$this->cnt_view;
    }
    
    /**
    * Получить URL просмотра информации о стране
    * @author Korshenko Alexey
    * @since  2011/11/23
    * 
    * @return string
    */ 
    public function getViewURL()
    {
        return $this->prefix_path . $this->getURL();
    }
    
    public function getPrefix_path()
    {
        return $this->prefix_path;
    }
    
    /**
    * Получить все фото страны
    * 
    */
    public function getPhoto()
    {
        return (array)CountryMediaManager::getInstance()
                                         ->getAll("country_id='{$this->getPk()}'", "weight asc");
    }
    
    /**
    * Получить фото страны для галереи
    * 
    */
    public function getGalleryPhoto()
    {
        return (array)CountryMediaManager::getInstance()->getAll("country_id='{$this->getPk()}' and type_id = ".CountryMediaManager::MEDIATYPE_ICON, "weight asc");
    }
    
    /**
    * Получить main фото страны
    * 
    */
    public function getMainPhoto()
    {

        if(false == $this->hasField("mainPhoto"))
        {
            $list = (array)CountryMediaManager::getInstance()->getAll("country_id='{$this->getPk()}' and type_id = ".CountryMediaManager::MEDIATYPE_ICON, "is_main desc, weight asc","0,1");
            $ex = null;
            if(count($list) > 0) $ex = current($list);
            if(!CountryMediaManager::getInstance()->isRootInstance($ex))         
                $ex = CountryMediaManager::getInstance()->cloneRootInstance();
                
            $this->addField("mainPhoto","object",$ex);        
        }
        return $this->mainPhoto;

    }
    
    /**
    * Получить Pager списка отелей
    * @author Korshenko Alexey
    * @since  2011/11/23
    * 
    * @return fvPager
    */ 
    public function getHotelList($params=array(),$perpage=null)
    {        
        $pager = new fvPager(HotelManager::getInstance());
        if (!is_null($perpage)) {
            $pager->setPaginatePerPage($perpage);
        }
        $where []= "country_id={$this->getPk()}";
        $params['resort_id'] = intval($params['resort_id']);
        if ($params['resort_id']>0) {
            $where[] = "resort_id={$params['resort_id']}";
        }
        $params['resort_id'] = intval($params['hotel_type_id']);
        if ($params['hotel_type_id']>0) {
            $where[] = "hotel_type_id={$params['hotel_type_id']}";
        }
        $params['hotel_name'] = trim($params['hotel_name']);
        if (strlen($params['hotel_name'])>0) {
            $params['hotel_name'] = addslashes($params['hotel_name']);
            $where[] = "name like '%{$params['hotel_name']}%'";
        }
        $where = count($where)>0 ? implode(" and ", $where) : array();
        $pager->paginate($where,"name asc");        
        return $pager;
    }
    public function getListResortByHotels()
    {
        $sql = " select inst.id, inst.name
                    from " . ResortManager::getInstance()->getTableName() . " inst
                        join " . HotelManager::getInstance()->getTableName() . " h
                            on (h.country_id={$this->getPk()} and inst.id=h.resort_id)
                  group by inst.id
                  order by inst.name";
        
        $_assoc = @fvSite::$DB->getAssoc($sql);
        $assoc = array("-1"=>"Любой");
        foreach ($_assoc as $id => $name) {
            $assoc[$id] = $name;
        }
        return $assoc;
    }
    public function getListHotelTypeByHotels()
    {
        $sql = " select inst.id, inst.name
                    from " . HotelTypeManager::getInstance()->getTableName() . " inst
                        join " . HotelManager::getInstance()->getTableName() . " h
                            on (h.country_id={$this->getPk()} and inst.id=h.hotel_type_id)
                  group by inst.id
                  order by inst.name";
        
        $_assoc = @fvSite::$DB->getAssoc($sql);
        $assoc = array("-1"=>"Любая");
        foreach ($_assoc as $id => $name) {
            $assoc[$id] = $name;
        }
        return $assoc;
    }
    /**
    * Увеличить счётчик просмотров страны
    * @author Korshenko Alexey
    * @since  2011/11/23
    * 
    */ 
    public function setCountView()
    {
        $sql = "update ".CountryManager::getInstance()->getTableName()." set cnt_view = cnt_view + 1 where id = ".$this->getPk();        
        $res = @fvSite::$DB->query($sql);
    }
}
