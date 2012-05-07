<?php

    class IndexModule extends fvModule {

        
        /**
        * Основной вывод списка новостей
        * @since 27.05.2011
        * @author Korniev Zakhar
        */
        function showIndex() 
        {
            fvSite::Import( fvSite::getConfig()->getPathByAlias("entities")."/Post.php", "Post" ) ;
            $post = fvSite::getSingleton('Post');
            
            $entityManager = fvSite::getDispatcher()->getApp()->getDb()->getEntityManager();

            
            $postEntity = $entityManager->find( 'post' , 1 );
            $mes = $postEntity->getTitle();
            $postEntity->setTitle( " XO XO XO " );
            
            
            print_r($postEntity);
            $uowSize = $entityManager->getUnitOfWork()->size();
            //$entityManager->persist($post);            
            $entityManager->flush();
            
            return array( "a" => $mes , "entitys" => $uowSize );
        }
        
        /**
        * Вывод пяти последних новостей
        * @since 27.05.2011
        * @author Korniev Zakhar
        */
        function showLatest() 
        {
            $permissionShow = 4;
            $packId = $this->getRequest()->getRequestParameter('packId', 'int', 0);
            $count = NewsManager::getSingleton()->getCount("is_promo = 1");
            
            $maxPack = ceil($count / $permissionShow);
            if($packId+1 > $maxPack)
                $packId = 0;
            
            $limit = $packId * $permissionShow;
            $this->cNews = NewsManager::getSingleton()->getAll( "is_promo = 1", "weight asc", "{$limit}, $permissionShow" );
            
            $this->__assign("packId", $packId);
            $this->__assign("isAjax", $this->getRequest()->isXmlHttpRequest());
            return $this->__display("latest.tpl");
        }
        
        /**
        * Просмотр одной новости по урл
        * @since 27.05.2011
        * @author Korniev Zakhar
        */
        public function showView()
        {            
            /**
            * @var News
            */
            $iNews = NewsManager::getSingleton()->getByUrl();
                                       
            if(NewsManager::getSingleton()->isRootInstance($iNews)) {                                

                if ($iNews->hasMeta())
                    $this->current_page->setMeta($iNews->getMeta());
                $this->current_page->setMetaTags(array(MetaManager::NEWS_HEADING=>$iNews->getHeading()));        
                $sesskey = __CLASS__ . __FUNCTION__ . $iNews->getPk();
                if (!fvSite::$fvSession->get($sesskey)) {
                    $iNews->incShows();    
                    fvSite::$fvSession->set($sesskey,true);
                }
                
            }
                            
            $this->__assign("iNews",$iNews);
                                     
            return $this->__display( "view.tpl" );
        }
        
        public function showSubscribe()
        {
            return $this->__display("subscribe.tpl");
        }

    }

?>
