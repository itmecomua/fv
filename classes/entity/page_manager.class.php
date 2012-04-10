<?php

require_once (fvSite::$fvConfig->get("path.entity") . 'page.class.php') ;

class PageManager extends fvRootManager {
	
	protected function __construct () {
	    $objectClassName = substr(__CLASS__, 0, -7);
	    
	    // Tweak for ManagerManager Class ;)
	    if ($objectClassName == "") $objectClassName = "Manager";
	    
	    $this->rootObj = new $objectClassName();
	}
	
    static function getInstance()
    {
        static $instance; 
        
        $className = __CLASS__;
        
        if (!isset($instance)) {
            $instance = new $className();
        }  
        return $instance;
    }

    function htmlSelect ($field, $empty = "", $where = null, $order = null, $limit = null, $args = array()) {
        $result = array('0' => 'корневая страница');
        
        if (!is_array($args)) $args = array(($args)?$args:'');
        
        $result = $result + parent::htmlSelect($field, $empty, $where, $order, $limit, $args);
        return $result;
    }
    
    public function getControl($current_page_id = null) {
        
        $where = "";
        if (!empty($current_page_id)) $where = " AND id <> " . intval($current_page_id);
        
        $Pages = $this->getAll("page_parent_id = 0 AND page_name <> 'default'{$where}");
        
        $result = array('0' => 'корневая страница');
        
        foreach ($Pages as $Page) {
            $result[$Page->getPk()] = $Page->get('page_name');
        }
        
        return $result;
    }
    
    public function getPagesByURL($url) {
        $params = array($url);
        
        $query = "SELECT f.* FROM ".$this->rootObj->getTableName()." f
LEFT JOIN ".$this->rootObj->getTableName()." f_p on (f_p.id = f.page_parent_id)
WHERE ? RLIKE CONCAT_WS(\"/\", IFNULL(f_p.page_url, ''), f.page_url)
order by IF (f.page_parent_id > 0, 1, IF(f.page_name <> 'default', 2, 3))";
        
        $res = fvSite::$DB->query($query, $params);

        $result = array();
        
        if (!DB::isError($res)) {
            while ($row = $res->fetchRow()) {
                $o = clone $this->rootObj;
                $o->hydrate($row);
                
                $result[] = $o;
            }
        }
        
        return $result;        
    }
}