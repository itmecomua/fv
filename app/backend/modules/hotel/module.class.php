<?php
/**
* Модуль управления отелями
*/
class HotelModule extends fvModuleDictionary
{

    function __construct () 
    {
        $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
        parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"), 
                            fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"), 
                            fvSite::$Layoult,
                            HotelManager::getInstance());        
    }
    /**
    * Редактирование отеля
    * 
    */
    function showEdit()
    {            
        $id = $this->getRequestParameter();
        $inst = $this->instance->getByPk($id,true);
        if (HotelManager::getInstance()->isRootInstance($inst)
            && CountryManager::getInstance()->isRootInstance($inst->getCountry())
            && !$inst->getCountry()->isNew()) {
                
            $listResort = ResortManager::getInstance()
                                       ->htmlSelect("name","Выбрать..","country_id={$inst->getCountry()->getPk()}");
        } else {
            $listResort = array(""=>"Выберите страну.");
        }
        
        $listCountry = CountryManager::getInstance()->htmlSelect("name","Выбрать..",null,"name asc");
        $listHotelType = HotelTypeManager::getInstance()->htmlSelect("name","Выбрать..",null,"name asc");
        
        $this->__assign("listHotelType",$listHotelType);
        $this->__assign("listResort",$listResort);
        $this->__assign("listCountry",$listCountry);
        
        return parent::showEdit($inst);
    }
    /**
    * Редактирование фото отеля
    * 
    */
    function showEditPhoto()
    {
        $id = $this->getRequestParameter("id","int",0);
        try {
            if ( $id < 1 ) 
                throw new EUserMessageError("Не указан id страны");
            $inst = HotelManager::getInstance()->getByPk($id);
            if (!HotelManager::getInstance()->isRootInstance($inst))
                throw new EUserMessageError("Страна не найдена");
                
        } catch (EUserMessageError $exc) {
            return $exc->getMessage();
        }
                
        $this->__assign("tmpDir",fvSite::$fvConfig->get("path.upload.web_temp_image"));        
        $this->__assign("inst", $inst);    
        $this->__assign("wt", HotelMediaManager::getInstance()->getListWeight() );
        
        return $this->__display("edit.photo.tpl");
    }
    /**
    * Перегрузить список курортов по стране
    * 
    */
    function showReloadResort()
    {
        $id = $this->getRequestParameter("id","int",0);
        try {
            if ( $id < 1 ) 
                throw new EUserMessageError("Не указан id страны");
            $listResort = ResortManager::getInstance()
                                        ->htmlSelect("name","Выбрать..","country_id={$id}","name asc");            
        } catch (EUserMessageError $exc) {
            return $exc->getMessage();
        }
                
        $this->__assign("listResort",$listResort);
        return $this->__display("edit.resort.tpl");
    }
    
}
