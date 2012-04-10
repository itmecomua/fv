<?php

class PagesModule extends fvModule {

    private $appName = 'frontend';
    private $appConfig = null;
    
    function __construct () 
    {
        $this->appConfig = new fvConfig(fvSite::$fvConfig->get("path.application.{$this->appName}.config"));
        $this->appConfig->Load("modules.yml");
        $this->appConfig->Load("template.yml");
        
        $this->moduleName = strtolower(substr(__CLASS__, 0, -6));

        parent::__construct(fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.template"), 
                            fvSite::$fvConfig->get("modules.{$this->moduleName}.smarty.compile"), 
                            fvSite::$Layoult);
    }

    function showIndex() 
    {
        $pager = new fvPager(PageManager::getInstance());
        $this->__assign('Pages', $pager->paginate(null, "IF (page_parent_id = 0, id*100000, page_parent_id*100000 + id)"));
        
        $request = fvRequest::getInstance();
        if (!$Page = PageManager::getInstance()->getByPk($request->getRequestParameter('id'))) 
        {
            $Page = new Page();
        }
        
        $this->__assign(array(
            'Page' => $Page,
            'PageManager' => PageManager::getInstance(),
            'metaManager' => MetaManager::getInstance()
        ));
        
        return $this->__display('page_list.tpl');
    }
    
    function showContentedit() 
    {
        $this->current_page->setTemplate('simpleLayoult.tpl');
        $this->__assign("XML_CONTENT", fvRequest::getInstance()->getRequestParameter("_xmlContent"));
        $this->__assign("NODE_ID", '');
        return $this->__display('content_edit.tpl');
    }
    
    function showGetpagecontent()
    {
        $this->current_page->setTemplate('simpleLayoult.tpl');
        
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $_xmlContent = fvRequest::getInstance()->getRequestParameter('_xmlContent');
        
        if ($dom->loadXML($_xmlContent)) {
            $xpth = new DOMXPath($dom);
            
            if ($nodeId = fvRequest::getInstance()->getRequestParameter('_nodeId')) {
                $currentNode = $xpth->evaluate("//*[@id='$nodeId']");
                
                if ($currentNode->length == 1) {
                    $currentNode = $currentNode->item(0);
                } else return '';
                
                if (fvRequest::getInstance()->hasRequestParameter("_add") && !fvRequest::getInstance()->getRequestParameter("_add")) {
                    $currentNode = $currentNode->parentNode;
                }
            } else {
                $currentPartName = fvRequest::getInstance()->getRequestParameter('_partName', null, $this->appConfig->get('template.default_content_part'));
                
                $currentNode = $xpth->evaluate("/page/content_part[@name='$currentPartName']");
                switch ($currentNode->length) {
                    case 0: 
                        $currentNode = $dom->createElement("content_part");
                        $currentNode->setAttribute('name', $currentPartName);
                        $currentNode->setAttribute('id', md5(microtime()));
                        $dom->documentElement->appendChild($currentNode);
                        break;
                    case 1:
                        $currentNode = $currentNode->item(0);
                        break;
                    default:
                        return '';
                        break;
                }
                $partNode = $currentNode;
            }
            
            $pathNodes = array();
            $lastNode = $currentNode;
            while ($lastNode->nodeName != "page") {
                if ($lastNode->nodeName == "content_part" && !$partNode) {
                    $partNode = $lastNode;
                }
                
                $pathNodes[] = array(
                    'id' => $lastNode->getAttribute("id"),
                    'name' => $lastNode->getAttribute("name"),
                    'current' => $lastNode->getAttribute("id") == $currentNode->getAttribute("id"),
                );
                $lastNode = $lastNode->parentNode;
            }
            
            
            $childNodes = array();
            foreach ($currentNode->childNodes as $childNode) {
                $childNodes[] = $childNode;
            }
            
            usort($childNodes, array($this, '_cmpNodes'));
            
            if (is_array($pathNodes))
                $pathNodes = array_reverse($pathNodes);
            
            $this->__assign(array(
                "_pageParts" => $this->appConfig->get("template.content_parts"),
                "_currentPart" => $partNode->getAttribute("name"),
                "_nodePath" => $pathNodes,
                "_currentNodes" => $childNodes,
            ));
            
            fvResponce::getInstance()->setHeader("X-JSON", json_encode(array(
                '_nodeId' => $currentNode->getAttribute("id"),
                '_xmlContent' => $dom->saveXML(),
            )));
            
            return $this->__display('show_page_content.tpl');
        }
        else return '';
    }
    
    function showAddpagenode() 
    {
        return $this->showGetpagecontent();
    }
    
    function showDeletepagenode() 
    {
        return $this->showGetpagecontent();
    }
    
    function showGetmoduleview() 
    {
        
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $_xmlContent = fvRequest::getInstance()->getRequestParameter('_xmlContent');
        
        if ($dom->loadXML($_xmlContent)) {
            $xpth = new DOMXPath($dom);
            
            if ($nodeId = fvRequest::getInstance()->getRequestParameter('_nodeId')) {
                $currentNode = $xpth->evaluate("//*[@id='$nodeId']");
                
                if ($currentNode->length == 1) {
                    $currentNode = $currentNode->item(0);
                } else return '';
                
                $currentView = $currentNode->getAttribute("view");
            }
        }
        
        $moduleName = fvRequest::getInstance()->getRequestParameter("module_name");
        $control = '<SELECT class="flat" style="width: 200px;" name="view" id="view">';
        
        foreach ((array)$this->appConfig->get("modules.{$moduleName}.shows") as $showType => $show) {
             $control .= '<OPTION value="' . $showType. '"' . (($showType == $currentView)?" selected":'') . '>' . $show;
        }
        
        return $control . "</SELECT>";
    }
    
    function showGetmoduleparams() 
    {
        $templateName = str_replace("new_", "", fvRequest::getInstance()->getRequestParameter("_type"));
         
        if (!fvRequest::getInstance()->getRequestParameter("_add")) {
            $dom = new DOMDocument();
            $dom->preserveWhiteSpace = false;
            $_xmlContent = fvRequest::getInstance()->getRequestParameter('_xmlContent');
        
            if ($dom->loadXML($_xmlContent)) {
                $xpth = new DOMXPath($dom);
                if ($nodeId = fvRequest::getInstance()->getRequestParameter('_nodeId')) {
                    $currentNode = $xpth->evaluate("//*[@id='$nodeId']");
                
                    if ($currentNode->length == 1) {
                        $currentNode = $currentNode->item(0);
                    } else return '';
                
                    $this->__assign(array(
                        "nodeName" => $currentNode->getAttribute('name'),
                        "nodeSize" => $currentNode->getAttribute('size'),
                        "nodeSpacer" => $currentNode->getAttribute('spacer'),
                        "margin" => $currentNode->getAttribute('margin'),
                        "width" => $currentNode->getAttribute('width'),
                        "padding" => $currentNode->getAttribute('padding'),
                        "floating" => $currentNode->getAttribute('floating'),
                        "name" => $currentNode->getAttribute('name'),
                        "attrs" => unserialize( $currentNode->getAttribute('attr') )
                    ));
                }
            }
        }
        $moduleList = array();
        foreach ($this->appConfig->get("modules") as $moduleName => $module) {
            if (!$module['system'])
                $moduleList[$moduleName] = $module['name'];
        }
         
        $this->__assign(array(
           "modulesList" => $moduleList,
           "_add" => fvRequest::getInstance()->getRequestParameter("_add"),
           "_nodeId" => fvRequest::getInstance()->getRequestParameter("_nodeId"),
           "_xmlContent" => fvRequest::getInstance()->getEscapedParameter("_xmlContent"),
        ));
         
        return $this->__display("$templateName.tpl");
    }
    function showGetmoduleparam() 
    {
        $moduleName = fvRequest::getInstance()->getRequestParameter("module_name");
        $moduleView = fvRequest::getInstance()->getRequestParameter("module_view");
        
        $parameters = $this->appConfig->get("modules.{$moduleName}.params.{$moduleView}");
        if (!is_array($parameters)) return '';
        $currentParams = array();
         
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $_xmlContent = fvRequest::getInstance()->getRequestParameter('_xmlContent');
        
        if ($dom->loadXML($_xmlContent)) {
            $xpth = new DOMXPath($dom);
            
            if ($nodeId = fvRequest::getInstance()->getRequestParameter('_nodeId')) {
                $currentNode = $xpth->evaluate("//*[@id='$nodeId']");
                
                if ($currentNode->length == 1) {
                    $currentNode = $currentNode->item(0);
                } else return '';
                
                $currentParams = unserialize($currentNode->getAttribute("parameters"));
                if (!is_array($currentParams)) $currentParams = array();
            }
        }
        
        $displayParams = array();
        foreach ($parameters as $name => $value) {
            $displayParams[] = array(
                "name" => $name,
                "label" => $value['name'],
                "value" => (array_key_exists($name, $currentParams))?$currentParams[$name]:$value['default'],
            );
        }
        
        if (count($displayParams) > 0) {
            $this->__assign('displayParams', $displayParams); 
            return $this->__display("module_p.tpl");
        } else return '';
    }

    private function _cmpNodes($a, $b) 
    {
        if ($a->getAttribute("order") == $b->getAttribute("order")) {
            return 0;
        }
        return ($a->getAttribute("order") < $b->getAttribute("order")) ? -1 : 1;
    }
}
