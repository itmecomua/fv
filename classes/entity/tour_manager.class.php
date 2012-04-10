<?php

require_once (fvSite::$fvConfig->get("path.entity") . 'tour.class.php') ;

 /**
 * Класс менеджер собственного тура
 * @author Korshenko Alexey
 * @since  2011/11/23
 * 
 */
class TourManager extends fvRootManager 
{
    const F_NAME = "F_NAME";
    const F_WEIGHT = "F_WEIGHT";
    const F_IS_SHOW = "F_IS_SHOW";
    const F_DURATION = "F_DURATION";
    const F_PRICE = "F_PRICE";
    const F_CNT_VIEW = "F_CNT_VIEW";
    protected $currentEntity = '';  
    protected function __construct () 
    {
        $objectClassName = substr(__CLASS__, 0, -7);        
        $this->_objectClassName = $objectClassName;
        $this->rootObj = new $objectClassName();        
    }
    
    static function getInstance()
    {
        static $instance; 
        if (!isset($instance))
            $instance = new self();
        return $instance;
    }
    
    public function getBackendListURL()
    {
        return fvSite::$fvConfig->get('dir_web_root') . strtolower($this->_objectClassName);               
    }
    
    public function getListBy($filter=array(),$order=array(),$page=0)
    {
        $f = (array) $filter;
        $where = array();
        if ($f[self::F_NAME]) 
        {
            $where[] = "tour.name like '%" . addslashes($f[self::F_NAME]) . "%'";
        }
      
        
         $where = count($where) > 0 ? " WHERE " . implode(" AND ",$where) : "";
         $orderBy = " ORDER BY ";
         switch ($order['field']) {
             case self::F_WEIGHT: $orderBy .= "tour.weight"; break;
             case self::F_NAME: $orderBy .= "tour.name"; break;
             case self::F_IS_SHOW: $orderBy .= "tour.is_show"; break;             
             case self::F_DURATION: $orderBy .= "tour.duration"; break;             
             case self::F_PRICE: $orderBy .= "tour.price_from"; break;             
             case self::F_CNT_VIEW: $orderBy .= "tour.cnt_view"; break;             
             default: $orderBy .="tour.id";
         }
         
         $orderBy .= " " . $order['direct'];
         $sql = " select tour.*
                    from {$this->getTableName()} tour
                    {$where}
                    {$orderBy}";
                    
        $addField=array();    
        $pager = new fvPager($this);        
        $list = $pager->paginateSQL($sql,null,$addField,$page);
        return $list;
    }
    
    public function getListWeight($default=null)
    {
        $arr = range(-20,20,1);
        $arr = array_combine($arr,$arr);
        if (!is_null($default)) {
            $ret = array(''=>$default);
            foreach ($arr as $k=>$v) 
               $ret[$k] = $v;
            return $ret;
        } else return $arr;
    }
    
    public function getListDuration($default=null)
    {
        $arr = range(1,31);
        $arr = array_combine($arr,$arr);
        if (!is_null($default)) {
            $ret = array(''=>$default);
            foreach ($arr as $k=>$v) 
               $ret[$k] = $v;
            return $ret;
        } else return $arr;
    }
    public function doImportTour()
    {
        $dom = new DOMDocument();
            $res = new stdClass();
            $res->cnt_new = 0;
            $res->cnt_delete = 0;
            $res->cnt_confirm = 0;
            $res->cnt_error = 0;
            $res->msg = "";        
        $exists = (array) fvSite::$DB->getAssoc("select import_id,id from {$this->getTableName()} where import_id is not null");
        
        @$dom->load("http://akkord-tour.com.ua/get-xml.php");
        try {
            $tours = $dom->getElementsByTagName("tours");
            if ($tours->length==0) {
                $res->cnt_error++;
                throw new Exception("Не найден тег tours");
            }
            $listTour = $tours->item(0)->getElementsByTagName("tour");
            if ($tours->length==0) {
                $res->cnt_error++;
                throw new Exception("Не найдены теги tour");
            }            
            $values = array();
            $translit = new Translit();
            
            foreach ($listTour as $tour) {
                $arrTourType = array();
                $arrTourType[] = $tour->getElementsByTagName("tour_type");
                $arrTourType[] = $tour->getElementsByTagName("travel_type");
                $dates = $tour->getElementsByTagName("dates");
                $countries = $tour->getElementsByTagName("countries");

                $upNode = array( "import_id" => $tour->getElementsByTagName("tour_id"),
                                 "name" => $tour->getElementsByTagName("name"),
                                 "price_from" => $tour->getElementsByTagName("price"),
                                 "short_text" => $tour->getElementsByTagName("route"),
                                 "duration" => $tour->getElementsByTagName("days"),
                                 "currency" => $tour->getElementsByTagName("currency"),
                                 "import_url" => $tour->getElementsByTagName("tour_url") );
                $up = array();
                foreach ($upNode as $fieldName => $node) {
                    if (is_object($node->item(0))) {
                        $up[$fieldName] = $node->item(0)->nodeValue;
                    }
                }
                $inst = $this->cloneRootInstance();
                $inst->updateFromRequest($up);
                // already exists {{
                    if (array_key_exists($inst->import_id,$exists)) {                 
                        $res->cnt_confirm++;
                        unset($exists[$inst->import_id]);
                        continue;
                    }
                // }}
                
                
                
                if (!$inst->currency) {
                    $inst->currency = "EUR";
                }
                                    
                $url = $translit->Transliterate($inst->name);
                if(TourManager::getInstance()->isRootInstance(TourManager::getInstance()->getOneByurl("{$url}"))) {
                    $url .= date("Ymd-His");
                }
                $inst->set("url",$url);
                
                if (!$inst->save()) {
                    $res->cnt_error++;                                       
                    continue;                        
                }
                if (is_object($dates) && preg_match_all("/\d{4}-\d{2}-\d{2}/",$dates->item(0)->nodeValue,$dates)) {
                    foreach ($dates[0] as $date) {
                        $date = date("Y-m-d",strtotime((string)$date));
                        if (Tour2DateManager::getInstance()->getCount("date_start='{$date}' and tour_id={$inst->getPk()}")==0) {
                            $t2d = Tour2DateManager::getInstance()->cloneRootInstance();
                            $t2d->set("date_start",$date);    
                            $t2d->set("tour_id",$inst->getPk());                                
                            @$t2d->save();    
                        }                            
                    }  
                    
                }
                if (is_object($countries->item(0))) {
                    foreach ($countries->item(0)->getElementsByTagName("country") as $country) {
                        $country = trim($country->nodeValue);
                        $instCountry = CountryManager::getInstance()->getOneByName($country);
                        if (!CountryManager::getInstance()->isRootInstance($instCountry)) {
                           $instCountry = CountryManager::getInstance()->cloneRootInstance();
                           $instCountry->set("name",$country); 
                           $instCountry->set("url",$translit->Transliterate($country)); 
                           $instCountry->save();
                        } 
                        
                        $t2c = Tour2CountryManager::getInstance()->cloneRootInstance();
                        $t2c->set("country_id",$instCountry->getPk());    
                        $t2c->set("tour_id",$inst->getPk());
                        
                        @$t2c->save();
                    }  
                    
                }
                foreach ($arrTourType as $tourType) {
                    if (is_object($tourType->item(0))) {                                                
                        $tourType = trim($tourType->item(0)->nodeValue);
                        $instTourType = TourTypeManager::getInstance()->getOneByName($tourType);
                        if (!TourTypeManager::getInstance()->isRootInstance($instTourType)) {
                           $instTourType = TourTypeManager::getInstance()->cloneRootInstance();
                           $instTourType->set("name",$tourType); 
                           $instTourType->set("url",$translit->Transliterate($tourType)); 
                           
                           @$instTourType->save();
                        } 
                        
                        $t2t = Tour2TypeManager::getInstance()->cloneRootInstance();
                        $t2t->set("type_id",$instTourType->getPk());    
                        $t2t->set("tour_id",$inst->getPk());
                        
                        @$t2t->save();
                    }                        
                }
                $res->cnt_new++;                               
            }
            
            if ($res->cnt_confirm > 0) {
                $del = array_keys((array)$exists);                
                if (count($del)>0) {
                    $res->cnt_delete = count($del);
                    fvSite::$DB->query("delete from {$this->getTableName()} where import_id in ('" . implode("','",$del) . "')" );
                }
            }     
            $res->msg = "Импорт выполнен успешно.";                        
            
        } catch (Exception $exc) {
            $res->msg = "{$exc->getMessage()} | file: {$exc->getFile()} line: {$exc->getLine()}";                        
        }
        fvDebug::debugs($res);
        return $res;
    }
    
}