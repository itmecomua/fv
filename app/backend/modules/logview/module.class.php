<?php

class LogViewModule extends fvModule {
   function __construct () 
    {
        $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
        parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"), 
        fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"), 
        fvSite::$Layoult);
    }

    function showIndex() {
        $filter = fvSite::$fvSession->get(fvRequest::getInstance()->getRequestParameter("requestURL")."/filter");
        
        $query = null;
        $params = array();
        if (is_array($filter)) {
            if (!empty($filter['object_name'])) {
                $query .= (($query)?" AND ":'') . "object_name LIKE ?";
                $params[] = '%'.$filter['object_name']."%";
                $this->__assign('filter_object_name', $filter['object_name']);
            }
            if (!empty($filter['date_from'])) {
                $query .= (($query)?" AND ":'') . "date >= ?";
                $params[] = $filter['date_from'];
                $this->__assign('filter_date_from', $filter['date_from']);
            }
            if (!empty($filter['date_to'])) {
                $query .= (($query)?" AND ":'') . "date <= ?";
                $params[] = $filter['date_to'];
                $this->__assign('filter_date_to', $filter['date_to']);
            }
            if (!empty($filter['message'])) {
                $query .= (($query)?" AND ":'') . "message LIKE ?";
                $params[] = '%'.$filter['message'].'%';
                $this->__assign('filter_message', $filter['message']);
            }
            if (!empty($filter['operation'])) {
                $query .= (($query)?" AND ":'') . "operation = ?";
                $params[] = $filter['operation'];
                $this->__assign('filter_operation', $filter['operation']);
            }
            if (!empty($filter['manager_id'])) {
                $query .= (($query)?" AND ":'') . "manager_id = ?";
                $params[] = $filter['manager_id'];
                $this->__assign('filter_manager_id', $filter['manager_id']);
            }
        }        
        $pager = new fvPager(LogManager::getInstance());
        $this->__assign('Logs', $pager->paginate($query, "date DESC", $params));
        $this->__assign('UserManager', UserManager::getInstance());
        return $this->__display('log_list.tpl');
    }
    
}

?>
