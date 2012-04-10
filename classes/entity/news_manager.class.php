<?php
  //news_manager.class.php

require_once (fvSite::$fvConfig->get("path.entity") . 'news.class.php') ;

class NewsManager extends fvRootManager 
{
    
    protected function __construct () 
    {
        $objectClassName = substr(__CLASS__, 0, -7);
        $this->rootObj = new $objectClassName();
    }
    
    static function getInstance()
    {
        static $instance; 
        
        $className = __CLASS__;
        
        if (!isset($instance)) 
        {
            $instance = new self();
        }  
        return $instance;
    } 
    
    /**
    * получить новость по урл
    * @author Korniev Zakhar
    * @since 27.05.2011
    */
    public function getByUrl()
    {
        $newsUrl = fvRequest::getInstance()->getRequestParameter( "news_url", "string", "" );
        $newsUrl = mysql_real_escape_string( $newsUrl );
        
        $iNews = self::getInstance()->getOneByurl( $newsUrl );
        return $iNews;
    }                       
}
  
?>
