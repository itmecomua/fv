<?php

    /**
    * Модуль отображения стран
    * @author Korshenko Alexey
    * @since  2011/11/23
    * 
    */ 
    class CountriesModule extends fvModule
    {
        function __construct ()
        {
            $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
            parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"),
            fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"),
            fvSite::$Layoult);            
        }

        /**
        * Отображение списка стран
        * @author Korshenko Alexey
        * @since  2011/11/23
        * 
        */ 
        function showIndex()
        {
            return $this->showList();
        }
        
        /**
        * Отображение списка стран
        * @author Korshenko Alexey
        * @since  2011/11/23
        * 
        */ 
        function showList()
        {
            $pager = new fvPager(CountryManager::getInstance());
            $pager->paginate("is_show = 1","name asc, name asc");            
            $this->__assign("countries",$pager);

            $full_list_country_name = CountryManager::getInstance()->htmlSelect("name", "", "is_show = 1", "name asc");
            $full_list_country_url  = CountryManager::getInstance()->htmlSelect("url",  "", "is_show = 1", "name asc");

            foreach ($full_list_country_url as $key => $value){
                $full_list_country_url[$key] = '/countries/view/' . $value;
            }
            

            $this->__assign( "full_list_country_name" , $full_list_country_name );
            $this->__assign( "full_list_country_url"  , $full_list_country_url  );
            
            return $this->__display("list.tpl");
        }
        /**
        * Отображение списка стран
        * @author Alexandr
        * @since  2011/12/5
        * 
        */        
        function showListlft()
        {
            $pager = new fvPager(CountryManager::getInstance());
            $pager->setPaginatePerPage(34); 
            $pager->paginate("is_show = 1","weight asc, name asc",null,null,0);                        
            $this->__assign("countries",$pager);
            return $this->__display("listlft.tpl");
        }
        
        /**
        * Получить отображение одной страны
        * @author Korshenko Alexey
        * @since  2011/11/23
        * 
        */ 
        function showView()
        {
            $url = fvRequest::getInstance()->getRequestParameter("url","string","");
            $country = CountryManager::getInstance()->getOneByurl($url);
            
            if(!CountryManager::getInstance()->isRootInstance($country)) return "Указанная страна не найдена";
            $country->setCountView();
            $this->__assign("country",$country);
            $this->__assign("hotelList",$country->getHotelList());
            return $this->__display("view.tpl");
        }
        
        /**
        * Получить список отелей старны
        * @author Korshenko Alexey
        * @since  2011/11/23
        * 
        */ 
        function showHotel()
        {
            $country_id = fvRequest::getInstance()->getRequestParameter("country_id","int",0);
            $country = CountryManager::getInstance()->getByPk($country_id);            
            if(!CountryManager::getInstance()->isRootInstance($country)) return "Указанная страна не найдена";
            $params = fvRequest::getInstance()->getRequestParameter("params");
            
            $listResort = $country->getListResortByHotels();
            $listHotelType = $country->getListHotelTypeByHotels();
            $this->__assign("listResort",$listResort);
            $this->__assign("listHotelType",$listHotelType);
            $this->__assign("hotelList",$country->getHotelList($params,32));
            $this->__assign("params",$params);
            
            return $this->__display("view.hotel.tpl");   
        }
        
        function showRoute()
        {
                $href       = fvRequest::getInstance()->getRequestParameter('fullurl');              
                $pointer    = fvRequest::getInstance()->getRequestParameter('pointer');              
                $pattern    = '/[\S]*'.$pointer.'[\/]?/';;
                $paramStr   = preg_replace( $pattern , '' , $href);
                $param      = array();
                 
                parse_str($paramStr,$param);
                if ( method_exists($this,$pointer) ) 
                {
                    return $this->$pointer($param);    
                }
                return false;


       }

        function showTour()
        {
                
            $country_id = fvRequest::getInstance()->getRequestParameter("country_id","int",0);
            $page = fvRequest::getInstance()->getRequestParameter("page","int",0);
            $country = CountryManager::getInstance()->getByPk($country_id);
            if(!CountryManager::getInstance()->isRootInstance($country)) return "Указанная страна не найдена";
            $pager = new fvPager(TourManager::getInstance());
            $where = array();
            $where[] = "inst.is_show = 1";
            $where[] = "t2c.country_id={$country_id}";
                                                                         
            $sql = "select inst.id,inst.name
                from " . TourTypeManager::getInstance()->getTableName() . " inst
                   join " . Tour2CountryManager::getInstance()->getTableName() . " t2c
                     on (t2c.country_id={$country_id})                   
                   join " . Tour2TypeManager::getInstance()->getTableName() . " t2t
                     on (t2t.type_id=inst.id and t2t.tour_id=t2c.tour_id)                     
                group by inst.id
                order by inst.name
                 ";            
            $tourType = array (-1=>"Любой");     
            $_tourType = @fvSite::$DB->getAssoc($sql);     
            
            if (count($_tourType)>0) {
                foreach ($_tourType as $id => $name) {
                    $tourType[$id] = $name;
                }
            }                        
            $params = $_params = fvRequest::getInstance()->getRequestParameter('params');
            $_params['country_id'] = $country_id;             
            $params['type_id'] = intval($params['type_id']);
            if ($params['type_id'] > 0) {
                $where[] = "t2t.type_id={$params['type_id']}";
            }
            
            $params['date_start_fr'] = trim($params['date_start_fr']);            
            if (strlen($params['date_start_fr']) > 0) {
                $where[] = "t2d.date_start>='" . date("Y-m-d",strtotime($params['date_start_fr'])) . "'";
            }
            $params['date_start_to'] = trim($params['date_start_to']);            
            if (strlen($params['date_start_to']) > 0) {
                $where[] = "t2d.date_start<='" . date("Y-m-d",strtotime($params['date_start_to'])) . "'";
            }
            $params['price_from'] = intval($params['price_from']);
            if ($params['price_from'] > 0) {
                $where[] = "inst.price_from<={$params['price_from']}";
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

            
            
            $this->__assign("tourType",$tourType);
            $this->__assign("params",$_params);            
            $this->__assign("tours",$pager);
            $this->__assign("country",$country);
            
            return $this->__display("view.tours.tpl");
        }
    }
