<?php

class ModuleLayoult extends fvLayoult 
{
    
    private $_pageInstance = null;
    private $_domInstance = null;
    
    function __construct(){
        $currentUrl = fvRoute::getInstance()->getModuleName();
        
        list($p) = (array) PageManager::getInstance()->getByPageUrl($currentUrl);        
        
        if (is_object($p)) $this->_pageInstance = $p;
        else fvDispatcher::redirect(fvSite::$fvConfig->get('page_404', 0, 404));
        
        $this->_domInstance = new DOMDocument();
        
        if (!($this->_domInstance->loadXML($this->_pageInstance->getPageContent()))) {
            fvDispatcher::redirect(fvSite::$fvConfig->get('page_error', 0, 302));
        }
                                                       
        $this->setMeta($this->_pageInstance->getMeta());
        $this->setCss($this->_pageInstance->css);
        $this->setJS($this->_pageInstance->js);
        fvSite::$Template->assign("Lang", fvLang::getInstance());
        fvSite::$Template->assign("codeManager", CodeDictionaryManager::getInstance());        
        parent::__construct("main.tpl");
    }
    
    function getPageContent() {
                    
    }
    
    function getPageContentPart($contentPartName) {
        $xpth = new DOMXPath($this->_domInstance);
        
        
        $currentPart = $xpth->evaluate("/page/content_part[@name='$contentPartName']");
        if ($currentPart->length == 1) {
            $currentPart = $currentPart->item(0);
        } else return false;//fvDispatcher::redirect(fvSite::$fvConfig->get('page_error', 0, 302));
               
        return $this->_parseNode($currentPart);
    }
    
    private function _parseNode($node) {
        $result = '';
        $childNodes = array();
        foreach ($node->childNodes as $childNode) {
            $childNodes[] = $childNode;
        }
        usort($childNodes, array($this, '__cmp'));
        
        switch ($node->nodeName) {
            case 'module':
                $moduleName = $node->getAttribute('name');
                $moduleView = $node->getAttribute('view');
                $moduleParams = unserialize($node->getAttribute('parameters'));
                $module = fvDispatcher::getInstance()->getModule($moduleName, "module");
                return $module->showModule($moduleView, $moduleParams, $node->getAttribute("id"));
            case 'current_module':
                return $this->getModuleResult();
            case 'vertical_layoult':
                $data = '';
                $data .= "<table style='width: 100%' valign='top'>";
                $heights = explode(',', $node->getAttribute('size'));
                $spacer = $node->getAttribute('spacer');
                foreach ($childNodes as $key => $childNode) {
                    $data .= "<tr" . (($heights[$key] != "*")?" height='{$heights[$key]}'":'') ."><td>".$this->_parseNode($childNode)."</td></tr>";
                    if ($spacer) {
                        $data .= "<tr height='{$spacer}px'><td>&nbsp;</td></tr>";
                    }
                }
                $data .= "</table>";
                return $data;
            case 'horisontal_layoult':
                $data = '';
                $data .= "<table style='width: 100%' valign='top'><tr>";
                $widths = explode(',', $node->getAttribute('size'));
                $spacer = $node->getAttribute('spacer');
                foreach ($childNodes as $key => $childNode) {
                    $data .= "<td valign=\"top\"" . (($widths[$key] != "*")?" width='{$widths[$key]}'":'') .">".$this->_parseNode($childNode)."</td>";
                    if ($spacer && ($key < (count($childNodes) - 1))) {
                        $data .= "<td width='{$spacer}px'>&nbsp;</td>";
                    }
                }
                $data .= "</tr></table>";
                return $data;
            case 'content_part':
                foreach ($childNodes as $childNode) {
                    $result .= $this->_parseNode($childNode);
                }
                break; 
                        case 'div':
                
                $attrs = unserialize( $node->getAttribute( 'attr' ) );
                $attrString = "";
                $hasStylePropery = false;
                
                if ( is_array( $attrs ) )
                foreach( $attrs as $attr )
                {        
                    if ( $attr["name"] == 'style' )
                    {    
                        $hasStylePropery = true;
                        $style  = $attr["value"];
                        $style .= $this->setStyle( $node );
                        $attrString .= " style=\"{$style}\" ";
                    }
                    else
                        $attrString .= " {$attr['name']}=\"{$attr['value']}\" ";
                }
                
                if ( !$hasStylePropery )
                    $attrString .= "style = \"{$this->setStyle($node)}\"";    
                    
                $data = "<div {$attrString}>";
                foreach ($childNodes as $key => $childNode) 
                {
                    $data .= $this->_parseNode($childNode);
                }
                $data .= '</div>';
                return $data;
                break;                       
        }
        return $result;
    }
    
    private function __cmp($a, $b) {
        
        if ($a->getAttribute('order') == $b->getAttribute('order')) return 0;
        
	    return ($a->getAttribute('order') < $b->getAttribute('order')) ? -1 : 1;
    }
    public function getPageInstance()
    {
        return $this->_pageInstance;
    }
    
}

?>
