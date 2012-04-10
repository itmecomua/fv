<?php
  class fvLang extends fvDebug
  {
    /* Массив с языками */  
    private $_langs;  
    
    /* Текущий язык */
    private $_curLang;    
    
    /* Язык по умолчанию */
    private $_defaultLang;
    
    /* Массив с переводами */
    private $_transliterate;
    
    /* Массив c языковыми ключами*/
    private $_keys;
    
    function __construct()
    {
         $this->_curLang = fvSite::$fvConfig->get('lang.current');
         $this->_defaultLang = fvSite::$fvConfig->get('lang.default');
         $this->_langs = array_keys((array)fvSite::$fvConfig->get('languages'));
         $this->_transliterate = fvSite::$fvConfig->get('transliterate.values');
         $this->_keys = fvSite::$fvConfig->get('transliterate.keys');
         
    }
    public static function getInstance()
    {
        static $instance;
        if(empty($instance))
              $instance = new self();
        return $instance;
    }
      
    
    public function __get($key_lang)
    {
        $item = $this->_transliterate[$key_lang][$this->_curLang];
        if(isset($item) && ( strlen($item) > 0 ) )
            return $item;
        $item = $this->_transliterate[$key_lang][$this->_defaultLang];
        if(isset($item) && ( strlen($item) > 0 ) )
            return $item;
        $this->setLangKey($key_lang);
        return "Translate not exist for [{$key_lang}]";
    }  
    
    /**
    * Получить текущий язык
    * @author Nesterenko Nikita
    * @since 2011/05/27
    * @return string
    */
    public function getCurLang()
    {
        return $this->_curLang ? $this->_curLang : $this->_defaultLang;
    }
    
    /**
    * Установить текущий язык
    * @author Nesterenko Nikita
    * @since 2011/05/27
    * @param string $lank_key
    */
    public function setCurLang($lang_key)
    {
        $this->_curLang = $lang_key;
    }
      
    /**
    * Получить данные о языке
    * @author Nesterenko Nikita
    * @since 2011/05/27
    * @param string $key  
    * @return array - параметры языка
    */
    public function getLangs($key = false)
    {
        return $key && $this->_langs[$key] ? $this->_langs[$key] : $this->_langs;
    }
    
    /**
    * Получить языковые ключи
    * @author Nesterenko Nikita
    * @since 2011/05/27
    * @param  
    * @return array
    */
    public function getKeys()
    {
        return $this->_keys;
    }
    /**
    * Получить переводы
    * @author Nesterenko Nikita
    * @since 2011/05/27
    * @param  
    * @return array
    */
    public function getTransliterate()
    {
        return $this->_transliterate;
    }
    /**
    * Сохранить массив с с переводами в виде сериализованного массива в файл
    * @author Nesterenko Nikita
    * @since 2011/05/27
    * @param  array $m
    * @returns bool
    */    
    public function saveConfig($m = array())
    {
        $langs = $this->_langs;
        $output = array();
        foreach($m as $key_name => $keysArr)
            foreach($keysArr as $lang_id => $key_value)
            {
                if(in_array($lang_id, $langs) && ( strlen($key_value) > 0 ) )
                    $output[$key_name][$lang_id] = $key_value;
            }        
        $langFilePath = fvSite::$fvConfig->get("path.config") . "languages.txt"; 
        if (file_exists($langFilePath))            
            $Transliterate = unserialize(file_get_contents($langFilePath));
        $Transliterate['values'] = $output;
        $langFile = fopen($langFilePath, "w+");
        fwrite($langFile, serialize($Transliterate));
        fclose($langFile);            
        return true;
    }
    
    /**
    * Установить ключ в массив ключей переводов
    * @author Nesterenko Nikita
    * @since 2011/04/20
    * @param string $lang_key
    * @returns bool
    */
    public function setLangKey($lang_key)
    {
        $langFilePath = fvSite::$fvConfig->get("path.config") . "languages.txt";
        if (file_exists($langFilePath))            
            $Transliterate = unserialize(file_get_contents($langFilePath));
            if(!in_array($lang_key, (array)$Transliterate['keys']))
                $Transliterate['keys'][] = $lang_key;
            $langFile= fopen($langFilePath, "w+");
            fwrite($langFile, serialize($Transliterate));
            fclose($langFile);
    }
    
    public function getDefaultLang()
    {
        return $this->_defaultLang;    
    }
  }
?>
