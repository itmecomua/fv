<?php
   class RequestProxy
   {
       const BRLINE = "\r\n";
       
       /**
       *  Error messages
       */
       const ERR_FUNCTION_EXISTS = "Не найдена функция для обработки запроса";
       const ERR_PATH_NOT_FOUND = "Не указан путь для обращения к серверу";
       /**
       * Proxy params
       */
       public  $_requestParam = "rp";
       /**
       * @return RequestProxy
       */
       static function getInstance()
       {
            static $instance;         
            $className = __CLASS__;        
            if (!isset($instance)) 
                $instance = new $className();
            return $instance;
       }
       protected function __construct () 
       {
       }       
       private function _sendCURL()
       {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->requestToString());
            curl_setopt($ch, CURLOPT_POST, 1);
            
            ob_start();
                curl_exec($ch);
                $res = ob_get_contents();
                ob_end_clean(); 
            ob_end_flush();
            curl_close($ch);
            
            return $res;
       }
      
       public function requestToString()
       {
           return $_GET[$this->_requestParam];
       }
       public function send()
       { 
                  
           try 
           {
               $res = $this->_sendCURL();
           } 
           catch(Exception $exc)
           {               
               return $exc->getMessage();
           }
           
           $this->result($res);
           
       }
       
       public function result($res)
       {
           echo $res;
       }
   }
   
             
   RequestProxy::getInstance()->send();
   

?>
