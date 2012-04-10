<?php

abstract class fvModule extends fvDebug
{
    protected $template_dir;
    protected $compile_dir;
    protected $current_page;
    protected $className;
    protected $moduleName;
    protected $TTL;
    protected $instance;
    
    public static $FV_NO_MODULE = ""; 
    
    function __construct($template, $compile, $current_page, fvRootManager $instance = null) 
    {
        $this->template_dir = $template;
        $this->compile_dir = $compile;
        $this->current_page = $current_page;
        $this->TTL = 1800;
        $this->instance = $instance;
    }

    function __set($name, $value) {
        $this->__assign($name, $value);
    }
    
    protected function getPage() 
    {
        return $this->current_page;
    }
    
    protected function __display($template_name) 
    {
        $this->__assign("module",$this->moduleName);
        $this->__assign("fvModule", $this);
        $template_name = $this->moduleName.".".$template_name;        
        $old_template_dir = fvSite::$Template->template_dir;
        $old_compile_dir = fvSite::$Template->compile_dir;
        
        fvSite::$Template->template_dir = $this->template_dir;
        fvSite::$Template->compile_dir = $this->compile_dir;
                               
        $result = fvSite::$Template->fetch($template_name);
        
        fvSite::$Template->template_dir = $old_template_dir;
        fvSite::$Template->compile_dir = $old_compile_dir;
        
        return $result;
    }
    
    public function ___d( $template_name )
    {
        return $this->__display($template_name);
    }
    
    public function ___a($key, $value = null)
    {
        return $this->__assign($key, $value);
    }


    protected function __assign($key, $value = null) {
        if (is_null($value)) {
            fvSite::$Template->assign($key);
        }
        else {
            fvSite::$Template->assign($key, $value);
        }
    }

    function showModule($module, $params = array(), $id = null) 
    {
        if (strlen((string)$module) == 0) $module = "index";
        $this->getParams()->setParameter("moduleID", $id);
        $moduleName = "show" . ucfirst(strtolower($module));
        
        if (is_callable(array($this, $moduleName))) 
        {
            return call_user_func(array($this, $moduleName), $params);
        }
        else return fvModule::$FV_NO_MODULE;
    }

    function getRequest() {
        return fvSite::$fvRequest;
    }

    function getParams() {
    	return fvSite::$fvParams;
    }
    
    protected function getRequestParameter($name = "id", $type = "int", $default = 0)
    {
        return $this->getRequest()->getRequestParameter($name, $type, $default);
    }
    
    
    public function getAdd()
    {
        $html = '<div class="operation">
                    <a href="' . fvSite::$fvConfig->get('dir_web_root') . $this->moduleName . '/edit" onclick="go(\'' . fvSite::$fvConfig->get('dir_web_root') . $this->moduleName . '/edit\'); return false;" class="add">добавить</a>
                </div>';
        return $html;
    }
    public function getReturn()
    {
        $html = '<div class="operation">
                    <a href="' . fvSite::$fvConfig->get('dir_web_root') . $this->moduleName . '" onclick="go(\'' . fvSite::$fvConfig->get('dir_web_root') . $this->moduleName . '\'); return false;" class="left">вернуться к списку</a>
                </div>';
        return $html;
    }
    
    public function getEdit( fvRoot $inst )
    {
        $html = '<a href="' . fvSite::$fvConfig->get('dir_web_root') . $this->moduleName . '/edit/?id='. $inst->getPk() . '" onclick="go(\'' . fvSite::$fvConfig->get('dir_web_root') . $this->moduleName . '/edit/?id='. $inst->getPk() . '\'); return false;">
                        <img src="' . fvSite::$fvConfig->get('dir_web_root') . 'img/edit_icon.png" title="редактировать" width="16" height="16">
                 </a>';
        return $html;
    }
    
    public function getDelete( fvRoot $inst )
    {
        $html = '<a href="javascript: void(0);" onclick="if(confirm(\'Вы действительно желаете удалить элемент?\')) go(\'' . fvSite::$fvConfig->get('dir_web_root') . $this->moduleName . '/delete/?id=' . $inst->getPk() . '\'); return false;">
                        <img src="' . fvSite::$fvConfig->get('dir_web_root') . 'img/delete_icon.png" title="удалить" width="16" height="16">
                 </a>';
        return $html;
    }
    
    public function showEdit()
    {
        if( is_null($this->instance ) )
            return "insatnce was empty : " . __METHOD__;
        $id = $this->getRequestParameter();
        $ex = $this->instance->getByPk( $id, true );
        $this->__assign("ex", $ex);
        return $this->__display("edit.tpl");
    }
}

?>
