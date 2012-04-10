<?php

class MenuModule extends fvModule {

    function __construct () 
    {
        $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
        parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"), 
        fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"), 
        fvSite::$Layoult);
    }

    function showMainMenu() 
    {
        $modules = fvSite::$fvConfig->get("modules");
                
        $this->currentModuleTree = array();
            
        foreach ($modules as $key => $module) {
            if (!$module['access']['enable'] || $this->current_page->getLoggedUser()->check_acl($module['access']['acl'])) {
                if (strlen(trim($module['menu_path'])) > 0) {
                    if (count($module_path = explode('/', $module['menu_path'])) < 2) {
                        $module_path[0] = 'другое';
                        $module_path[1] = $module['menu_path'];
                    }
            
                    $this->currentModuleTree[md5($module_path[0])]['name'] = $module_path[0];
                    $this->currentModuleTree[md5($module_path[0])]['child_nodes'][] = array(
                        'name'          => $module_path[1],
                        'image_name'    => $module['icon'],
                        'href'          => fvSite::$fvConfig->get("dir_web_root") . "$key/",
                    );
                }
            }
        }
          
       // uasort($this->currentModuleTree, array($this, '_cmpModules'));
                
        $this->__assign('currentModuleTree', $this->currentModuleTree);
        return $this->__display('menu.tpl');
    }
    
    private function _find_in_array ($a, $array) {
        foreach ($array as $key => $value) {
            if ($value === $a) return $key;
        }
        return false;
    }
        
    private function _cmpModules($a, $b) {
        if (is_array($a['child_nodes'])) {
            
            if ($key = $this->_find_in_array($a, $this->currentModuleTree))
                uasort($this->currentModuleTree[$key]['child_nodes'], array($this, '_cmpModules'));
        }
        if (is_array($b['child_nodes'])) {
            if ($key = $this->_find_in_array($b, $this->currentModuleTree))
                uasort($this->currentModuleTree[$key]['child_nodes'], array($this, '_cmpModules'));
        }
        
        if ($a['name'] == $b['name']) {
            return 0;
        }
        return ($a['name'] < $b['name']) ? -1 : 1;
    }
    
    
    function showIndex($p)
    {
        $where = array();
        $parent_id = (int)$p['parent_id'];
        $type = $p['type'];
        if($parent_id)
             $where[] = "parent_id = '{$parent_id}'";
        else $where[] = "parent_id IS NULL";
        $where[] = "type_id = " . MenuManager::TYPE_HORIZONTAL;
        $where = implode(" AND ", $where);
        
        $ListH = MenuManager::getInstance()->getAll($where, "weight asc");  
        
        $where = array();
        $parent_id = (int)$p['parent_id'];
        if($parent_id)
             $where[] = "parent_id = '{$parent_id}'";
        else $where[] = "parent_id IS NULL";
        $where[] = "type_id = " . MenuManager::TYPE_VERTICAL;
        $where = implode(" AND ", $where);  
        $ListV = MenuManager::getInstance()->getAll($where, "weight asc");
        
        $this->__assign("ListH", $ListH);
        $this->__assign("ListV", $ListV);
        $this->__assign("isParent", $parent_id);
        $this->__assign("manager", MenuManager::getInstance() );
        return $this->__display( $parent_id ? "index.list.{$type}.tpl" : "index.tpl");
    }
    
    function showEdit()
    {
        $id = $this->getRequestParameter();
        
        $ex = MenuManager::getInstance()->getByPk($id, true);
        
        if($ex->isNew()) $ex->set("type_id",fvRequest::getInstance()->getRequestParameter("t","int",1));
                         
        $types = MenuManager::getInstance()->getTypeMenu();
        $arr = array();
        $menuTreeH = $ex->getMenuTree(false, $arr, null, MenuManager::TYPE_HORIZONTAL);
        
        $arr = array();
        $menuTreeV = $ex->getMenuTree(false, $arr, null, MenuManager::TYPE_VERTICAL);
        
        $this->__assign("types", $types);
        $this->__assign("ex", $ex);
        $this->__assign("menuTreeH", $menuTreeH);
        $this->__assign("menuTreeV", $menuTreeV);
        $this->__assign("manager", MenuManager::getInstance());
        return $this->__display("edit.tpl");
    }
}

?>
