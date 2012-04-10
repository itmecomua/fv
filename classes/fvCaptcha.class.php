<?php

/**
* Капчакласс
*/
class fvCaptcha
{
    const DIR_TECH = 'path.tech_captcha_root'; 
    
    const DIR_WEB = 'path.web_captcha_root';    
    const SALT = "__abrakadabra__";
    
    const MSG_DO_REFRESH = "Информация на странице устарела. Обновите страницу, пожалуйста.";
    const MSG_DO_CHECK = "Не соответствует тексту на картинке";
    
    const FORM_KEY = "captcha";
    
    protected $_message = '';
    private $_fileName = '';
    protected function __construct ()
    {
    }
    static function getInstance($fileName=null)
    {
        static $instance;
        if (!is_null($fileName)) {
            $instance = new self();
            $instance->setFileName($fileName);
        } elseif (!isset($instance)) {
            $instance = new self();
        }
        return $instance;
    }
    
    private function setFileName($fileName)
    {
        $this->_fileName = $fileName;
    }
    private function generateFileName($text)
    {
        $fileName = md5( $text . self::SALT ) . ".png";
        return $fileName;
    }
    function check($fileName,$text)
    {                    
        if ( $this->unlink($fileName) ) {
            $check = $this->generateFileName($text) === $fileName;
            if (! $check )
                $this->setMessage( self::FORM_KEY,self::MSG_DO_CHECK );
        }
        
        return (bool) $check;
    }
    function generate()
    {        
        $font= fvSite::$fvConfig->get("path.font")."norobot_font.ttf";        
        $text=substr(md5(rand(0, 100)),0,5);
        
        $im=imagecreate(180, 56);
        $w=imagecolorallocate($im, 255, 255, 255);
        $b=imagecolorallocate($im, 90, 90, 90);
        $g1=imagecolorallocate($im, 180, 180, 180);
        $g2=imagecolorallocate($im, 100, 100, 100);
        $g3=imagecolorallocate($im, 170, 200, 220);
        $cl1=imagecolorallocate($im, rand(0, 150), rand(0, 150), rand(0, 150));
        $cl2=imagecolorallocate($im, rand(0, 150), rand(0, 150), rand(0, 150));
        $cl3=imagecolorallocate($im, rand(0, 150), rand(0, 150), rand(0, 150));
        $cl4=imagecolorallocate($im, rand(0, 150), rand(0, 150), rand(0, 150));
        $cl5=imagecolorallocate($im, rand(0, 150), rand(0, 150), rand(0, 150));
        for($i=0; $i <= 180; $i += 4 ) { imageline($im, $i, 0, $i, 55, $g1); }
        for($i=0; $i <= 55; $i += 4 ) { imageline($im, 0, $i, 180, $i, $g1); }
        for($i=0; $i <= 180; $i += 45 ) { imageline($im, rand(-2, 18) + $i, rand(-2, 18), rand(38, 58) + $i, rand(38, 58), $g3); }
        for($i=0; $i <= 180; $i += 45 ) { imageline($im, rand(-2, 18) + $i, rand(38, 58), rand(38, 58) + $i, rand(-2, 18), $g3); }
        @imagettftext($im, rand(28, 32), rand(-30, 30), 10 + rand(0, 6), 40 + rand(-10, 10), $cl1, $font, substr($text, 0, 1));
        @imagettftext($im, rand(28, 32), rand(-30, 30), 45 + rand(-6, 6), 40 + rand(-10, 10), $cl2, $font, substr($text, 1, 1));
        @imagettftext($im, rand(28, 32), rand(-30, 30), 80 + rand(-6, 6), 40 + rand(-10, 10), $cl3, $font, substr($text, 2, 1));
        @imagettftext($im, rand(28, 32), rand(-30, 30), 115 + rand(-6, 6), 40 + rand(-10, 10), $cl4, $font, substr($text, 3, 1));
        @imagettftext($im, rand(28, 32), rand(-30, 30), 150 + rand(-6, 6), 40 + rand(-10, 10), $cl5, $font, substr($text, 4, 1));
        for($i=1; $i <= 14; $i++ ) { imageline($im, rand(0, 90), rand(0, 60), rand(0, 90), rand(0, 60), $g2); }
        for($i=1; $i <= 14; $i++ ) { imageline($im, rand(90, 180), rand(0, 60), rand(90, 180), rand(0, 60), $g2); }
        imagerectangle($im , 0, 0, 179, 55, $b);
        $k=1.9;
        $im1=imagecreatetruecolor(180 * $k, 56 * $k);
        $im2=imagecreatetruecolor(180, 56);
        imagecopyresized($im1, $im, 0, 0, 0, 0, 180 * $k, 56 * $k, 180, 56);
        imagecopyresampled($im2, $im1, 0, 0, 0, 0, 180, 56, 180 * $k, 56 * $k);        

        $fileName = $this->generateFileName($text);        
        imagepng($im2,fvSite::$fvConfig->get(self::DIR_TECH) . $fileName);
        imagedestroy($im);
        imagedestroy($im1);
        imagedestroy($im2);
        
        return self::getInstance($fileName);
   }
   public function getFullSource()
   {
       return "http://" . fvSite::$fvConfig->get('server_name') . fvSite::$fvConfig->get(self::DIR_WEB) . $this->_fileName;
   }
   public function getFileName()
   {
       return $this->_fileName;
   }
   protected function unlink($fileName)
   {        
       $fileName = fvSite::$fvConfig->get(self::DIR_TECH) . $fileName;       
                 
       if ( !file_exists($fileName) )
          $this->setMessage( self::FORM_KEY,self::MSG_DO_REFRESH );
       else return @unlink($fileName);       
   }
   protected function setMessage($key,$msg)
   {
       $this->_message[ $key ] = $msg;
   }
   public function getValidationResult()
   {
       return $this->_message;
   }
   public function render( $class=null )
   {
       $class = is_null($class) ? " class='{$class}' " : '';
       return "<img src='{$this->getFullSource()}' {$class}>"
              . "<input type='hidden' name='captcha[fileName]' value='{$this->getFileName()}' />";
   }
   
}

  
?>