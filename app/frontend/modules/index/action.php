<?php
class IndexAction extends fvAction {
    /*
    function __construct () 
    {
        parent::__construct(fvSite::$Layoult);
    }
    */
    function executeIndex() 
    {
        
        
        return array("a"=>100500 , "ao" => __FILE__ );
    }
    
    function executeLatest() 
    {
        if (!fvRequest::getSingleton()->isXmlHttpRequest()) 
        {
            return self::$FV_OK;
        }
        else 
        {
            return self::$FV_AJAX_CALL;
        }   
    }
    
    function executeView() 
    {
        if (!fvRequest::getSingleton()->isXmlHttpRequest()) 
        {
            return self::$FV_OK;
        }
        else 
        {
            return self::$FV_AJAX_CALL;
        }   
    }
    function executeSubscribe() 
    {
        if (!fvRequest::getSingleton()->isXmlHttpRequest()) 
        {
            return self::$FV_OK;
        }
        else 
        {
            return self::$FV_AJAX_CALL;
        }   
    }
    
    function executeSaveSubscribe()
    {
        $request = $this->getRequest();
        if( !$request->isXmlHttpRequest() ) return $this->redirect404();
        try {
            $email = $request->getRequestParameter('email', 'string');
            $iSubscribe = SubscribeManager::getSingleton()->cloneRootInstance();
            $m['email'] = $email;
            $m['is_active'] = 1;
            $iSubscribe->updateFromRequest($m);
            if( !$iSubscribe->isValid() )
                throw new EUserMessageError(fvLang::getSingleton()->error_to_send, $iSubscribe, "sub_" );
            
            if( !$iSubscribe->save() )
                throw new EUserMessageError(fvLang::getSingleton()->error_to_send, $iSubscribe, "sub_" );    
             $this->setHeader('message', json_encode(fvLang::getSingleton()->succesfully_sended) );
        } catch (EUserMessageError $exc ) {
            $this->setHeader('exception', json_encode($exc->getMessage()));
            $this->setHeader('validation', json_encode($exc->getValidationResult()));
        }        
        return self::$FV_AJAX_CALL;
    }
    
}

?>
