<?php

class UserGroupsModule extends fvModule 
{

    function __construct () 
    {
        $this->moduleName = strtolower(substr(__CLASS__, 0, -6));
        parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"), 
        fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"), 
        fvSite::$Layoult);
    }

    function showIndex() 
    {
        $pager = new fvPager(UserGroupManager::getInstance());
        $this->__assign('UserGroups', $pager->paginate(null, "default_group DESC"));
        return $this->__display('group_list.tpl');
    }
    
    function showEdit() 
    {
        $request = fvRequest::getInstance();
        if (!$UserGroup = UserGroupManager::getInstance()->getByPk($request->getRequestParameter('id'))) 
        {
            $UserGroup = new UserGroup();
        }

        $this->__assign('UserGroup', $UserGroup);
        return $this->__display('group_edit.tpl');
        
    }
}

?>
