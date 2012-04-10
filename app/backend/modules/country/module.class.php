<?php
/**
* Модуль управления странами
*/
class CountryModule extends fvModuleDictionary
{

    function __construct () 
    {
        $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
        parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"), 
                            fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"), 
                            fvSite::$Layoult,
                            CountryManager::getInstance());        
    }
    
    function showEdit()
    {
        $this->__assign("listWeight",CountryManager::getInstance()->getListWeight());        
        $this->__assign("yesno",array("0"=>"Нет","1"=>"Да"));        
        return parent::showEdit();
    }
    /**
    * Редактирование фото страны
    * 
    */
    function showEditPhoto()
    {
        $id = $this->getRequestParameter("id","int",0);
        try {
            if ( $id < 1 ) 
                throw new EUserMessageError("Не указан id страны");
            $inst = CountryManager::getInstance()->getByPk($id);
            if (!CountryManager::getInstance()->isRootInstance($inst))
                throw new EUserMessageError("Страна не найдена");
                
        } catch (EUserMessageError $exc) {
            return $exc->getMessage();
        }
                
        $this->__assign("tmpDir",fvSite::$fvConfig->get("path.upload.web_temp_image"));        
        $this->__assign("inst", $inst);    
        $this->__assign("wt", CountryMediaManager::getInstance()->getListWeight() );
        
        return $this->__display("edit.photo.tpl");
    }

    
}
