<?php
class UsersModule extends fvModule 
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
        $pager = new fvPager(UserManager::getInstance());
        $this->__assign('Users', $pager->paginate());
        return $this->__display('user_list.tpl');
    }

    function showEdit()
    {
        $request = fvRequest::getInstance();
        if (!$User = UserManager::getInstance()->getByPk($request->getRequestParameter('id'))) 
        {
            $User = new User();
        }

        $this->__assign(array('User' => $User,"GroupManager" => UserGroupManager::getInstance()));
        
        return $this->__display('user_edit.tpl');
    }
}

?>
