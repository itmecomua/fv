<?php
class OrderAction extends fvAction {
    
    function __construct () 
    {
        parent::__construct(fvSite::$Layoult);
    }
    
    function executeIndex() 
    {
        if (!fvRequest::getInstance()->isXmlHttpRequest()) 
        {
            return self::$FV_OK;
        }
        else 
        {
            return self::$FV_AJAX_CALL;
        }   
    }
    function executeSave()
    {
         try {
            $inst = OrderManager::getInstance()->cloneRootInstance();
            $up = $this->getRequestParameter("update","array",array());            
            $captcha = $this->getRequestParameter("captcha","array",array());
            
            if (!fvCaptcha::getInstance()->check($captcha["fileName"],$captcha["inputfield"])) {                                
                throw new EUserMessageError("Не правильно введены символы с картинки");
            }
            $up["date_fr"] = $up["date_fr"] ? date("Y-m-d",strtotime($up["date_fr"])) : null;
            $up["date_to"] = $up["date_to"] ? date("Y-m-d",strtotime($up["date_to"])) : null;
            
            $inst->updateFromRequest($up);            
            if (!$inst->isValid()) {                
                throw new EUserMessageError("Проверьте правильность заполнения полей",$inst,"order_");
            }
            if ( $inst->save() ) {
                fvResponce::getInstance()->setHeader( 'message', json_encode( 'Выполнено' ) );                
                //fvResponce::getInstance()->setHeader( 'redirect',json_encode($inst->getUrlEdit()) );
            } else {
                throw new EUserMessageError("Ошибка сохранения данных. Повторите попытку позже");
            }
        } catch (EUserMessageError $exc) { 
            fvResponce::getInstance()->setHeader( 'captcha', json_encode( fvCaptcha::getInstance()->generate()->render() ) );           
            fvResponce::getInstance()->setHeader( 'exception', json_encode( $exc->getMessage() ) );
            fvResponce::getInstance()->setHeader( 'validation', json_encode( $exc->getValidationResult() ) );            
        }
        return self::$FV_AJAX_CALL; 
    }
}