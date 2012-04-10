<?php

class SubscribeAction extends fvAction {
    
	function __construct () {
	    parent::__construct(fvSite::$Layoult);
	}
    
    function executeIndex() 
    {
        if (!fvRequest::getInstance()->isXmlHttpRequest()) {
            return self::$FV_OK;
        } else {
            return self::$FV_AJAX_CALL;
        }   
    }
    function executeSubscribe()
    {
        
        try {
            $inst = SubscribeManager::getInstance()->cloneRootInstance();
            $up = $this->getRequestParameter("update","array",array());            
            $inst->updateFromRequest($up);            
            if (!$inst->isValid()) {                
                throw new EUserMessageError("Проверьте правильность заполнения полей",$inst,"subscr_");
            }
            if ( $inst->save() ) {
                fvResponce::getInstance()->setHeader( 'message', json_encode( 'Выполнено' ) );                
                //fvResponce::getInstance()->setHeader( 'redirect',json_encode($inst->getUrlEdit()) );
            } else {
                throw new EUserMessageError("Ошибка сохранения данных. Повторите попытку позже");
            }
        } catch (EUserMessageError $exc) {            
            fvResponce::getInstance()->setHeader( 'exception', json_encode( $exc->getMessage() ) );
            fvResponce::getInstance()->setHeader( 'validation', json_encode( $exc->getValidationResult() ) );            
        }
        return self::$FV_AJAX_CALL;             
    }
    function executeUnsubscribe()
    {
         try {
            $inst = SubscribeManager::getInstance()->cloneRootInstance();
            $up = $this->getRequestParameter("update","array",array());
            $inst->set("email",$up['email']);
            $inst->set("name",$up['name']);

            if (!$inst->doValidateEmail($inst->email) || !$inst->doValidateEmpty($inst->name)) {
               throw new EUserMessageError("Проверьте правильность заполнения полей",$inst,"unsubscr_"); 
            }
            $inst = SubscribeManager::getInstance()->getOneByEmail($inst->email);
            if (!SubscribeManager::getInstance()->isRootInstance($inst)) {                
                throw new EUserMessageError("Запись с таким email не найдена");
            }
            $inst->is_active = 0;
            if ( $inst->save() ) {
                fvResponce::getInstance()->setHeader( 'message', json_encode( 'Выполнено' ) );                
                //fvResponce::getInstance()->setHeader( 'redirect',json_encode($inst->getUrlEdit()) );
            } else {
                throw new EUserMessageError("Ошибка. Повторите попытку позже");
            }
        } catch (EUserMessageError $exc) {            
            fvResponce::getInstance()->setHeader( 'exception', json_encode( $exc->getMessage() ) );
            fvResponce::getInstance()->setHeader( 'validation', json_encode( $exc->getValidationResult() ) );
            return self::$FV_AJAX_CALL;             
        }
        return self::$FV_AJAX_CALL;             
    }
        
}
