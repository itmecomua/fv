<?php

abstract class fvLayoult {
    
    protected $_title;
    protected $_keywords;
    protected $_description;
    protected $_templateName;
    protected $_moduleResult;
    
    public function __construct($templateName) {
        $this->_templateName = $templateName;
    }
    
    public function getTitle() {    
        return $this->_title;
    }
    
    public function getKeywords() {
        return $this->_keywords;
    }
    
    public function getDescription() {
        return $this->_description;
    }
    
    public function setTitle($title) {
        $this->_title = $title;
    }
    
    public function setKeywords($keywords) {
        $this->_keywords = $keywords;
    }
    
    public function setDescription($description) {
        $this->_description = $description;
    }

    public function setTemplate($templateName) {
        $this->_templateName = $templateName;
    }
    
    public function showPage() {
        if (!is_null($this->_templateName)) {
            fvSite::$Template->assign(array(
                'currentPage' => $this                
            ));
            return fvSite::$Template->fetch($this->_templateName);
        } else return $this->getPageContent();
    }
    
    public function setModuleResult($result) {
        $this->_moduleResult = $result;
    }
    
    public function getModuleResult() {
        return $this->_moduleResult;
    }
    
    public function getLoggedUser() {
        return fvSite::$fvSession->getUser();
    }
    
    abstract public function getPageContent();
    
    public function getCss()
    {
       $data = $this->_css;
       $arr = explode("|",$data);
       $output = "<!-- start page css -->";
       foreach($arr as $key=>$val)
       {
           if($val)
                $output .=  '<link rel="stylesheet" type="text/css" href="'.$val.'" />';
       }
       
       return $output."<!-- end page css -->";
    }
    
    public function setCss($css)
    {
       $this->_css = $css;
    }
    
    public function getJS()
    {
       $data = $this->_js;
       $arr = explode("|",$data);
       $output = "<!-- start page js -->"; 
       foreach($arr as $key=>$val)
       {
           if($val)
                $output .=  '<script type="text/javascript" src="'.$val.'"></script>';
       }
       
       return $output."<!-- end page js -->";
    }
    
    public function setJS($js)
    {
       $this->_js = $js;
    }
    public function setMeta(Meta $meta)
    {
        $this->setTitle($meta->getTitle());
        $this->setDescription($meta->getDescription());
        $this->setKeywords($meta->getKeywords());        
    }
  /**
    * Установка метатегов страницы
    * 
    * @param array $arrTags
    * @param array $arrVal
    * @param string $delimiter
    */
    public function setMetaTags($arrTags = array(), $delimiter=',')
    {                             
        $arrTagSite = array (
            'title'=> trim($this->_title),
            'description' => trim($this->_description),
            'keywords' => trim($this->_keywords)
        );
        
        $req = fvRequest::getInstance();
        $default = $req->getRequestParameter('module') . ' :: ' . $req->getRequestParameter('action');        
        
        
        if ($arrTags){
            foreach ($arrTags as $tag=>$value){
               
                if (is_array($value)) {                    
                    $value = implode($delimiter,$value); 
                }

                $arrTagSite = preg_replace("(%{$tag}%)", $value, $arrTagSite);
            }                                                            
        }    

        $this->_title = $arrTagSite['title'] ? $arrTagSite['title'] : $default;
        $this->_description = $arrTagSite['description'] ? $arrTagSite['description'] : $default;
        $this->_keywords = $arrTagSite['keywords'] ? $arrTagSite['keywords'] : $default; 
        
    }
}

?>
