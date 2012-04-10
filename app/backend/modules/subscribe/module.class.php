<?php
/**
* Модуль управления подписками
*/
class SubscribeModule extends fvModuleDictionary
{

    function __construct () 
    {
        $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
        parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"), 
                            fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"), 
                            fvSite::$Layoult,
                            SubscribeManager::getInstance());        
    }
    function showIndex()
    {                                
        $listIsActive = array( "-1" => "Любой",
                               "0" => "Не активен",
                               "1" => "Активен" );
        $this->__assign("listIsActive",$listIsActive);
        return parent::showIndex();
    }
    /**
    * Скачивание CSV файла
    * 
    */
    function showDownloadCSV()
    {                
        $search = $this->getRequestParameter("search","array",array());
        $fields = $this->getRequestParameter("fields","array",array());
        
        $list = SubscribeManager::getInstance()->getListBy($search,null,null,false);
        
        $csv = array();
        foreach ($list as $item) {
            $tmp = array();
            foreach ($fields as $field) {
                $tmp[] = $item->$field;
            }
            $csv[] = implode(",",$tmp);
        }
        $fileName = "subscribe-" . date("Ymd-His") . ".csv";
                
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename={$fileName}");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        $csv = implode("\r\n",$csv);        
        return $csv;
    }
}
