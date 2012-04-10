<?php

    /**
    * Модуль отображения отелей
    * @author Dmitriy Khoroshylov
    * @since  2011/11/30
    * 
    */ 
    class HotelsModule extends fvModule
    {
        function __construct ()
        {
            $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
            parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"),
            fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"),
            fvSite::$Layoult);            
        }

        /**
        * Отображение списка отелей
        * @author Dmitriy Khoroshylov
        * @since  2011/11/30
        * 
        */ 
        function showIndex()
        {
            return $this->showList();
        }
        
        /**
        * Отображение списка отелей
        * @author Dmitriy Khoroshylov
        * @since  2011/11/30
        * 
        */ 
        function showList()
        {
            $pager = new fvPager(HotelManager::getInstance());
            $pager->paginate(null,"name asc");
            $this->__assign("hotels",$pager);
            return $this->__display("list.tpl");
        }
        
        /**
        * Получить отображение одноого отеля
        * @author Dmitriy Khoroshylov
        * @since  2011/11/30
        * 
        */ 
        function showView()
        {
            
            $url = fvRequest::getInstance()->getRequestParameter("url","string","");
            $hotel = HotelManager::getInstance()->getOneByurl($url);
            if(!HotelManager::getInstance()->isRootInstance($hotel)) return "Указанный отель не найден";
            

            
            $hotel->setCountView();
            $this->__assign("hotel",$hotel);
            
            $template = "";
            
            //$this->getRequest()->isXmlHttpRequest()?$template = "viewajax.tpl":$template = "view.tpl";
            if($this->getRequest()->isXmlHttpRequest())
            {
                $template = "viewajax.tpl";
            }
            else
            {
                $template = "view.tpl";
            }
                
            return $this->__display($template);
            
        }
    }