<?php

    /**
    * Модуль отображения туров
    * @author Dmitriy Khoroshylov
    * @since  2011/11/30
    * 
    */ 
    class ToursModule extends fvModule
    {
        function __construct ()
        {
            $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
            parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"),
            fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"),
            fvSite::$Layoult);            
        }

        /**
        * Отображение списка туров
        * @author Dmitriy Khoroshylov
        * @since  2011/11/30
        * 
        */ 
        function showIndex()
        {
            return $this->showList();
        }
        function showListtourtypelft()
        {                          
            $pager = new fvPager(TourTypeManager::getInstance());
            $pager->setPaginatePerPage(34);
            $pager->paginate("is_show = 1","weight asc, name asc",null,null,0);            
            
            $this->__assign("tourtypes",$pager);
            return $this->__display("listtourtypelft.tpl");
        } 
        /**
        * Отображение списка туров
        * @author Dmitriy Khoroshylov
        * @since  2011/11/30
        * 
        */ 
        function showList()
        {
            $pager = new fvPager(TourManager::getInstance());
            $where = array();
            $where[] = "inst.is_show = 1";
            $tourType = $this->getRequestParameter("tour_type_url","string","");            
            $page = $this->getRequestParameter("page","int",0);
                            
            $instTourType = $tourType != TourTypeManager::URL_ALL  
                                ? TourTypeManager::getInstance()->getOneByurl($tourType) 
                                : TourTypeManager::URL_ALL;
            
            if (TourTypeManager::getInstance()->isRootInstance($instTourType)) {
                $where[] = "t2t.type_id={$instTourType->getPk()}";
            } else {
                $tourType = TourTypeManager::URL_ALL;
            }
            
            $sql = "select inst.url,inst.name
                from " . TourTypeManager::getInstance()->getTableName() . " inst
                   join " . Tour2TypeManager::getInstance()->getTableName() . " t2t
                     on (t2t.type_id=inst.id AND inst.is_show=1 )
                group by inst.id
                order by inst.name
                 ";            
            $tourType = array (TourTypeManager::URL_ALL=>"Любой");     
            $_tourType = @fvSite::$DB->getAssoc($sql);     
            
            if (count($_tourType)>0) {
                foreach ($_tourType as $id => $name) {
                    $tourType[$id] = $name;
                }
            }
            
            $sql = "select c.id, c.name
                from " . CountryManager::getInstance()->getTableName() . " c 
                   join " . Tour2CountryManager::getInstance()->getTableName() . " t2c
                     on (t2c.country_id=c.id)
                   join " . TourManager::getInstance()->getTableName() . " inst
                     on (inst.id=t2c.tour_id)   
                group by c.id
                order by c.name";
                                         
            $listCountry = array (""=>"Любая");     
            $_listCountry = @fvSite::$DB->getAssoc($sql);     
            
            if (count($_listCountry)>0) {
                foreach ($_listCountry as $id => $name) {
                    $listCountry[$id] = $name;
                }
            }                          
            $params = $_params = fvRequest::getInstance()->getRequestParameter('params');
            
            $params['date_start_fr'] = trim($params['date_start_fr']);            
            if (strlen($params['date_start_fr']) > 0) {
                $where[] = "t2d.date_start>='" . date("Y-m-d",strtotime($params['date_start_fr'])) . "'";
            }
            $params['date_start_to'] = trim($params['date_start_to']);            
            if (strlen($params['date_start_to']) > 0) {
                $where[] = "t2d.date_start<='" . date("Y-m-d",strtotime($params['date_start_to'])) . "'";
            }
            $params['price_from'] = $params['price_from']?intval($params['price_from']):"";
            if ($params['price_from'] > 0) {
                $where[] = "inst.price_from<={$params['price_from']}";
            }
            $params['country_id'] = intval($params['country_id']);
            if ($params['country_id'] > 0) {
                $where[] = "t2c.country_id={$params['country_id']}";
            }
            $where = implode(" and ",$where);
            
            $sql = "select inst.*
                from " . TourManager::getInstance()->getTableName() . " inst
                   left join " . Tour2CountryManager::getInstance()->getTableName() . " t2c
                     on (t2c.tour_id=inst.id)
                   left join " . Tour2TypeManager::getInstance()->getTableName() . " t2t
                     on (t2t.tour_id=inst.id)
                   left join " . Tour2DateManager::getInstance()->getTableName() . " t2d
                     on (t2d.tour_id=inst.id)
                where {$where}
                group by inst.id
                order by inst.id
                 ";           
            $pager->paginateGroupSQL($sql,null,array(),$page);
            $pagerURL = "/tours/list/{$instTourType->url}?".http_build_query(array("params"=>$params))."&page=";
                                                   
            $this->__assign("tourType",$tourType);
            $this->__assign("listCountry",$listCountry);
            $this->__assign("currTourType",$instTourType->url);
            
            $this->__assign("instTourType",$instTourType);
            
            $this->__assign("params",$_params);            
           
            $this->__assign("pagerURL",$pagerURL);
            $this->__assign("tours",$pager);
            
            return $this->__display("list.tpl");
        }
        
        /**
        * Получить отображение одноого тура
        * @author Dmitriy Khoroshylov
        * @since  2011/11/30
        * 
        */ 
        function showView()
        {
            $url = fvRequest::getInstance()->getRequestParameter("url","string","");
            $tour = TourManager::getInstance()->getOneByurl($url);
            
            if(!TourManager::getInstance()->isRootInstance($tour)) return "Указанный тур не найден ....";
            $tour->setCountView();
            $this->__assign("tour",$tour);   
            
            return $this->__display("view.tpl");
        }
        
    }