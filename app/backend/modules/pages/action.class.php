<?php

class PagesAction extends fvAction {
    
    function __construct () {
        parent::__construct(fvSite::$Layoult);
    }

    function executeIndex() {
        if (fvRequest::getInstance()->isXmlHttpRequest())
            return self::$FV_NO_LAYOULT;
        else return self::$FV_OK;
    }
    
    function executeEdit() {
        if (fvRequest::getInstance()->isXmlHttpRequest())
            return self::$FV_AJAX_CALL;
        else return self::$FV_OK;
    }
    
    function executeSave() 
    {
        $request = fvRequest::getInstance();
        
        if (!$Page = PageManager::getInstance()->getByPk($request->getRequestParameter('id'))) {
            $Page = new Page();
        }
        $p = $request->getRequestParameter('p');
        $meta = $request->getRequestParameter('meta');
        
        if ($Page->isNew()) {
            $defaultPage = PageManager::getInstance()->getByPageName('default');
            if (is_object($defaultPage = $defaultPage[0])) {
                if (!$p['page_title']) $p['page_title'] = $defaultPage->page_title;
                if (!$p['page_description']) $p['page_description'] = $defaultPage->page_description;
                if (!$p['page_keywords']) $p['page_keywords'] = $defaultPage->page_keywords;
                if (!$p['page_content']) $p['page_content'] = $defaultPage->page_content;
            }
        }
        $Page->addField("oldImage",'string',$Page->image);
        $Page->updateFromRequest($p);
        $Page->getMeta()->updateFromRequest($meta);
        
        if ($Page->setMeta($Page->getMeta()) && $Page->save() ) {
            fvResponce::getInstance()->setHeader('Id', $Page->getPk());
            $this->setFlash("Данные успешно сохранены", self::$FLASH_SUCCESS);
            fvResponce::getInstance()->setHeader('redirect', fvSite::$fvConfig->get('dir_web_root') . $request->getRequestParameter('module') . "/?id=". $Page->getPk());        
        } else { 
            fvResponce::getInstance()->setHeader('X-JSON', json_encode($Page->getValidationResult()));
            $this->setFlash("Ошибка при сохранении данных проверте правильность введенных данных", self::$FLASH_ERROR);
        }
        
        if (fvRequest::getInstance()->isXmlHttpRequest())
            return self::$FV_AJAX_CALL;
        else return self::$FV_OK;
    }    

    function executeDelete() {
        $request = fvRequest::getInstance();
        if (!$Page = PageManager::getInstance()->getByPk($request->getRequestParameter('id'))) {
            $this->setFlash("Ошибка при удалении.", self::$FLASH_ERROR);
        } else {
            $Page->getMeta()->delete();
            $Page->delete();            
            $this->setFlash("Данные успешно удалены", self::$FLASH_SUCCESS);
        }
        
        fvResponce::getInstance()->setHeader('redirect', fvSite::$fvConfig->get('dir_web_root') . $request->getRequestParameter('module') . "/");
        if (fvRequest::getInstance()->isXmlHttpRequest())
            return self::$FV_NO_LAYOULT;
        else return self::$FV_OK;
    }

    function executeContentedit() {
        if (fvRequest::getInstance()->isXmlHttpRequest())
            return self::$FV_AJAX_CALL;
        else $this->redirect404();
    }
    
    function executeGetpagecontent () {
        if (fvRequest::getInstance()->isXmlHttpRequest())
            return self::$FV_AJAX_CALL;
        else $this->redirect404();
    }
    
    function executeDeletepagenode () {
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $_xmlContent = fvRequest::getInstance()->getRequestParameter('_xmlContent');
        
        if ($dom->loadXML($_xmlContent)) {
            $xpth = new DOMXPath($dom);
            if ($nodeId = fvRequest::getInstance()->getRequestParameter('_nodeId')) {
                $currentNode = $xpth->evaluate("//*[@id='$nodeId']");
                if ($currentNode->length != 1) {
                    if (fvRequest::getInstance()->isXmlHttpRequest())
                        return self::$FV_AJAX_CALL;
                    else $this->redirect404();
                } else $currentNode = $currentNode->item(0);
                
                fvRequest::getInstance()->putRequestParameter("_nodeId", $currentNode->parentNode->getAttribute("id"));
                
                $parent = $currentNode->parentNode;
                $dOrder = $currentNode->getAttribute("order");
                
                for ($i = 0; $i < $parent->childNodes->length; $i++) {
                    if (($order = $parent->childNodes->item($i)->getAttribute("order")) > $dOrder) {
                        $parent->childNodes->item($i)->setAttribute("order", $order - 1);
                    }
                }
                
                $currentNode->parentNode->removeChild($currentNode);
                
                fvRequest::getInstance()->putRequestParameter("_xmlContent", $dom->saveXML());
            }
        }
        
        if (fvRequest::getInstance()->isXmlHttpRequest())
            return self::$FV_NO_LAYOULT;
        else return self::$FV_OK;
    }
    
    function executeAddpagenode () 
    {
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $_xmlContent = fvRequest::getInstance()->getRequestParameter('_xmlContent');
        
        if ($dom->loadXML($_xmlContent)) {
            $xpth = new DOMXPath($dom);
            if ($nodeId = fvRequest::getInstance()->getRequestParameter('_nodeId')) {
                $currentNode = $xpth->evaluate("//*[@id='$nodeId']");
                if ($currentNode->length != 1) {
                    if (fvRequest::getInstance()->isXmlHttpRequest())
                        return self::$FV_AJAX_CALL;
                    else $this->redirect404();
                } else $currentNode = $currentNode->item(0);
                
                $nodeName = str_replace("new_", "", fvRequest::getInstance()->getRequestParameter('_nodeName'));
                
                if (fvRequest::getInstance()->getRequestParameter('_add')) {
                    $newNode = $dom->createElement($nodeName);
                    $newNode->setAttribute("id", md5(microtime()));
                    $newNode->setAttribute("order", $currentNode->childNodes->length);
                    $currentNode->appendChild($newNode);
                } else {
                    $newNode = &$currentNode;
                }
              
                $newNode->setAttribute("name", fvRequest::getInstance()->getEscapedParameter('name'));
                
                if (strpos($nodeName, "layoult") !== false) {
                    $newNode->setAttribute("size", fvRequest::getInstance()->getEscapedParameter('size'));
                    $newNode->setAttribute("spacer", fvRequest::getInstance()->getEscapedParameter('spacer'));
                } elseif (strpos($nodeName, "current") === false) {
                    $newNode->setAttribute("view", fvRequest::getInstance()->getEscapedParameter('view'));
                    $newNode->setAttribute("parameters", serialize(fvRequest::getInstance()->getRequestParameter('parameters')));
                }
                
                if ( $nodeName == 'div' ) {
                    $newNode->setAttribute("name", fvRequest::getInstance()->getEscapedParameter('name'));  
                    $newNode->setAttribute("width", fvRequest::getInstance()->getEscapedParameter('width'));  
                    $newNode->setAttribute("padding", fvRequest::getInstance()->getEscapedParameter('padding'));  
                    $newNode->setAttribute("margin", fvRequest::getInstance()->getEscapedParameter('margin'));  
                    $newNode->setAttribute("floating", fvRequest::getInstance()->getRequestParameter('floating', 'string', 'auto'));  
                    
                    $attrs = fvRequest::getInstance()->getRequestParameter( 'attr','array', array() );
                    $newNode->setAttribute("attr", serialize( $attrs ) );  
                }
                
                fvRequest::getInstance()->putRequestParameter("_xmlContent", $dom->saveXML());
            }
        }
        
        
        if (fvRequest::getInstance()->isXmlHttpRequest())
            return self::$FV_AJAX_CALL;
        else $this->redirect404();
    }
    
    function executeGetmoduleparams () {
        if (fvRequest::getInstance()->isXmlHttpRequest())
            return self::$FV_AJAX_CALL;
        else $this->redirect404();
    }    
    
    function executeGetmoduleparam () {
        if (fvRequest::getInstance()->isXmlHttpRequest())
            return self::$FV_AJAX_CALL;
        else $this->redirect404();
    }
    
    function executeGetmoduleview () {
        if (fvRequest::getInstance()->isXmlHttpRequest())
            return self::$FV_AJAX_CALL;
        else $this->redirect404();
    }
    
    function executeReorder () {
        
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $_xmlContent = fvRequest::getInstance()->getRequestParameter('_xmlContent');
        
        if ($dom->loadXML($_xmlContent)) {
            $xpth = new DOMXPath($dom);
            if ($nodeId = fvRequest::getInstance()->getRequestParameter('_nodeId')) {
                $order = explode(",",fvRequest::getInstance()->getRequestParameter('_nodeOrder'));
                if (!is_array($order)) $order = array();
                
                $currentNode = $xpth->evaluate("//*[@id='$nodeId']/*");
                foreach ($currentNode as $reorderNode) {
                    $reorderNode->setAttribute('order', $order[$reorderNode->getAttribute('order')]);
                }
                fvResponce::getInstance()->setHeader("X-JSON", json_encode(array(
                    '_xmlContent' => $dom->saveXML(),
                )));            
            }
        }
        
        if (fvRequest::getInstance()->isXmlHttpRequest())
            return self::$FV_AJAX_CALL;
        else $this->redirect404();
    }
}

?>
