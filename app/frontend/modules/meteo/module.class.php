<?php

class MeteoModule extends fvModule 
{
        public $exportURL = "http://export.spo.ua/meteo/getdata";
        function __construct () 
        {
            $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
            parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"), 
            fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"), 
            fvSite::$Layoult);
        }

        function showIndex($data) 
        {
           $container = fvRequest::getInstance()->getRequestParameter("container","string",false);
           $city = fvRequest::getInstance()->getRequestParameter("spo_code","int","0");
           $date = fvRequest::getInstance()->getRequestParameter("date","string",date("Y-m-d"));
                
           $url = $this->exportURL . "?city={$city}&date={$date}"; 
              //fvDebug::debugs($url);    
           $obj = @simplexml_load_file($url);                                  
            //fvDebug::debugs($obj);  
           if(isset($obj->city)) {               
               $meteo = $obj->city;
               foreach ($obj->city as $key => $inst) {                                     
                   if ((int)$inst->hour < (int)date("H")) 
                   {
                       $meteo = $inst;                   
                   }                                          
               } 
               $this->__assign("meteo", array($meteo));
           }
           $data = $this->__display("index.tpl");
           if ($container)
               return "document.getElementById('{$container}').innerHTML =" . json_encode($data) . ";";
           return $data;
        } 
        function showPanel($data) 
        {
            
            $where = "spo_code <> 0 ";        
            
            $Resort = ForecastCityManager::getInstance()->htmlSelect("city_name",null,null,"city_name");
            $this->__assign("export", $data['export']);
            $this->__assign("resMeteo", $Resort);
            $this->__assign("dateMeteo", array(0=>"Выбор", date("Y-m-d")=>"Сегодня",date('Y-m-d',strtotime("+1 day"))=>"Завтра" ));
            $this->__assign("today",date("Y-m-d"));
            return $this->__display("panel.tpl");
        }  

}