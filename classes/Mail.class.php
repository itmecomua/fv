<?php
 
class Mail
{
      private $from_email;
      private $from_name;
      private $bcc;
      private $error_email; 
      protected $exTempl;
      private $extra;
      private $no_bcc; 
      private $reg_exp_email;

      public function __construct($data=array(),$extra = array(), $useronly=false)
      {
          $this->from_email = fvSite::$fvConfig->get("email.sender");
          $this->from_name = fvSite::$fvConfig->get("email.from_name");
          $this->bcc = fvSite::$fvConfig->get("email.bcc");
          $this->error_email = fvSite::$fvConfig->get("email.error_email");
          $this->reg_exp_email = "/^[a-z0-9_\-\.]+@[a-z0-9_\-\.]+\.[a-z]{2,3}$/i";
         
          if (count($extra))
          {
            $this->extra = $extra;  
          }
          
          $this->no_bcc =  $useronly;

      }
      public function SendMail($email,$name="",$data='')
      {
          $isValidEmail = ( preg_match($this->reg_exp_email, $email) > 0 );
          if( !$isValidEmail )
                return false;
          
         
         $letter .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\"><head><META http-equiv='Content-type' content='text/html; charset=utf-8' /></head><body>";
         //$letter .= iconv("UTF-8",fvSite::$fvConfig->get( "email.encoding" ),$this->exTempl->template);
         $letter .= $data;
         $letter .= "</body></html>";

         if (count($this->extra))
         {
           
           $params = "";  
           foreach ($this->extra as $key => $value) 
           {
               $params.="&$key=".urlencode($value);
           }
           
           $pattern1 = '@(]*href=")([^>\"]*)("[^>]*>)@i';
           $pattern2 = '@(]*href=\')([^>\"]*)(\'[^>]*>)@i';
           
           $letter = preg_replace_callback($pattern1,create_function('$matches',' $url = $matches[2];$url = urlencode($url);return $matches[1]."http://spo.ua/subscribe/go/?url=".$url."'.$params.'".$matches[3];'),$letter);               
           $letter = preg_replace_callback($pattern2,create_function('$matches',' $url = $matches[2];$url = urlencode($url);return $matches[1]."http://spo.ua/subscribe/go/?url=".$url."'.$params.'".$matches[3];'),$letter);               
         }

            // картинки - абсолютные пути
            $site = fvSite::$fvConfig->get("server_name");

            $letter = str_replace("src='/","src='http://$site/",$letter);
            $letter = str_replace("src=\"/","src=\"http://$site/",$letter);
         
         
         
            
 
         $mime_mail = new html_mime_mail ( );
         $mime_mail->add_html($letter);
         $mime_mail->build_message("k");

         /*if($this->no_bcc) return $mime_mail->SendMail ( $this->from_email, iconv("UTF-8",fvSite::$fvConfig->get( "email.encoding" ),$this->from_name), $email , iconv("UTF-8",fvSite::$fvConfig->get( "email.encoding" ),$name), iconv("UTF-8",fvSite::$fvConfig->get( "email.encoding" ),$this->exTempl->theme),"text/plain");         
         else return $mime_mail->SendMail ( $this->from_email, iconv("UTF-8",fvSite::$fvConfig->get( "email.encoding" ),$this->from_name), $email , iconv("UTF-8",fvSite::$fvConfig->get( "email.encoding" ),$name), iconv("UTF-8",fvSite::$fvConfig->get( "email.encoding" ),$this->exTempl->theme),"text/plain",null,$this->bcc);*/
         if($this->no_bcc) 
         return $mime_mail->SendMail ( $this->from_email, $this->from_name, $email , $name, $name,"text/plain");         
         else return $mime_mail->SendMail ( $this->from_email, $this->from_name, $email , $name, $name,"text/plain",null,$this->bcc);

      }
      
      public function SendErrorMail()
      {
         $letter .= "<html><head><META http-equiv='Content-type' content='text/html; charset=koi8-r' /></head><body>";
         $letter .= iconv("UTF-8",fvSite::$fvConfig->get( "email.encoding" ),$this->exTempl->template);
         $letter .= "</body></html>";

         $mime_mail = new html_mime_mail ( );
         $mime_mail->add_html($letter);
         $mime_mail->build_message("k");

         return $mime_mail->SendMail ( $this->from_email, iconv("UTF-8",fvSite::$fvConfig->get("email.encoding"),$this->from_name),  $this->error_email , iconv("UTF-8",fvSite::$fvConfig->get( "email.encoding" ),$name), iconv("UTF-8",fvSite::$fvConfig->get( "email.encoding" ),$this->exTempl->theme),"text/plain");
      }

      public function setFrom($email,$name)
      {
         $this->from_email=$email;
         $this->from_name=$name;
      }
      
      public function setFromName($name)
      {         
         $this->from_name=$name;
      }
      
      public function setTheme($theme)
      {
         $this->exTempl->theme=$theme;
      }
      
      public function addBcc($bcc)
      {
          if(is_array($bcc)){
         foreach ($bcc as $key => $value) 
         {
             $this->bcc.=", $value";
         }
          }
          else
          {
              $this->bcc.=", $bcc";
          }
          
      }
      public function setHtml($html)
      {
          $this->exTempl->template = $html;
      }
}

?>
