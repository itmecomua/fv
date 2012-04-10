<?php

/**
 *  Base model class 
 */
abstract class fvRoot implements ArrayAccess {

    /**
     * fields array, each field has type `field_name` => array (value, type, changed). Not include id field and auto date fields
     */ 
    protected $fields;
    protected $key;
    protected $keyName;
    protected $tableName;
    protected $valid;
    
    const UPDATE = 'Update';
    const INSERT = 'Insert';
    protected static $databaseOperation = array(self::UPDATE => DB_AUTOQUERY_UPDATE, self::INSERT => DB_AUTOQUERY_INSERT);
    protected static $serializeTypes = array('array', 'object');
    
/**
     * 
     */
    function __construct ($fields, $tableName, $keyName = "id") { 
        $this->fields = $fields;
        $this->tableName = $tableName;
        $this->keyName = $keyName;  
        $this->valid = array();
        $this->doChangeMultiLang();
    }
    
    protected function _setUnChanged($fieds = null) {
        if (is_null($fieds)) {
            foreach ($this->fields as &$field) {
                $field['changed'] = false;
            }
        }
        else {
            if (!is_array($fieds)) $fieds = array($fieds);
            
            foreach ($fieds as $field) {
                if (isset($this->fields[$field]))
                    $this->fields[$field]["changed"] = false;
                else throw new EFieldError("No field'{$field}' is specified");
            }
        }
    }
    
    public static function getSerializedTypes() {
        return self::$serializeTypes;
    }
    
    function getFieldList() {
        return array_keys($this->fields);
    }
    
    function getFields()
    {
        return $this->fields;
    }

    function isChanged($fieldName) {
        if (isset($this->fields[$fieldName]))
            return $this->fields[$fieldName]["changed"];
        else throw new EFieldError("No field '{$fieldName}' is specified");
    }
    
    function get($fieldName, $defaultValue = '') {
        if (isset($this->fields[$fieldName])) {
            $return = (!is_null($this->fields[$fieldName]["value"]))?$this->fields[$fieldName]["value"]:$defaultValue;
            if (gettype($return) != $this->fields[$fieldName]["type"] && $this->fields[$fieldName]["type"] != 'date') {
                @settype($return, $this->fields[$fieldName]["type"]);
            }
            return $return;
        }
        else throw new EFieldError("No field'{$fieldName}' is specified");
        
    }
    
    function set($fieldName, $newValue) {
        if ( isset($this->fields[$fieldName]) ) { 
            
            if (($this->fields[$fieldName]["value"] != $newValue) || ($this->isNew())) {
                $valid = true;
                   
                if (isset($this->fields[$fieldName]["validate"]) && $this->fields[$fieldName]["validate"]) {
                    $validateMethod = "validate" . ucfirst(strtolower($fieldName));

                    if (method_exists($this, $validateMethod)) {
                        $valid = $this->$validateMethod($newValue);
                    }
                }

                if ($valid) {
                    @settype($newValue, $this->fields[$fieldName]['type']);
                    
                    if ($this->fields[$fieldName]['pre_func'] && function_exists($this->fields[$fieldName]['pre_func'])) { 
                        $newValue = call_user_func($this->fields[$fieldName]['pre_func'], $newValue);
                    }

                    $this->fields[$fieldName]["value"] = $newValue;
                    $this->fields[$fieldName]["changed"] = true;
                }
                else {
                    $this->fields[$fieldName]["value"] = $newValue;
                    
                    return false;
                }
            }
            else return false;
                    
        }
        else throw new EFieldError("No field '{$fieldName}' is specified");
        
        return true;
    }
    
    function addField ($fieldName, $type, $value = null, $validate = false) {
        if (empty($fieldName) || empty($type)) throw new EFieldError("field name and type are required");
        
        $this->fields[$fieldName] = array ("value" => $value, "type" => $type, "changed" => false, "validate" => $validate);
    }
    
    function removeField ($fieldName) {
        if (empty($fieldName)) throw new EFieldError("field name is required");
        if (!isset($this->fields[$fieldName])) throw new EFieldError("No field'{$fieldName}' is specified");
        
        unset($this->fields[$fieldName]);
    }
    
    function __get($name) {
        return $this->get($name, null);
    }
    
    function __set($name, $value) {
        return $this->set($name, $value);
    }
    
    function save($logging = true) {
        if ($this->isNew()) {
            return $this->saveToDatabase(self::INSERT, $logging);
        } else {
            return $this->saveToDatabase(self::UPDATE, $logging);
        }
        return false;
    }
    
    protected function saveToDatabase($saveType, $logging) 
    {
        if( $this->isMultiLangEntity() )
            return $this->saveToDatabaseLang($saveType, $logging); 
        if ($this->isValid()) {
            $insertList = array();
            
            foreach ($this->getFieldList() as $field) {
                if ($this->isChanged($field) || $this->autoValue($field, $saveType)) {
                    if (in_array($this->getFieldType($field), self::$serializeTypes)) {
                         $insertList[$field] = serialize($this->get($field));
                    }
                    else {
                        if ($this->getFieldType($field) == 'set') {
                            $insertList[$field] = implode(',',$this->get($field));
                        } else $insertList[$field] = $this->get($field);
                    }
                }
            }
            
            if (count($insertList) > 0) {
                if ($saveType == self::INSERT) {
                    $dbResult = fvSite::$DB->autoExecute($this->getTableName(), $insertList, self::$databaseOperation[$saveType]);
                } else {
                    
                    if (is_array($keyNames = $this->getPkName())) {
                        $where = '';
                         foreach ($keyNames as $key) {
                             $where .= (($where)?" AND ":'') . $key . " = " . $this->getPk($key);
                         }
                    } else {
                        $where = $this->getPkName() . " = " . $this->getPk();
                    }
                    
                    $dbResult = fvSite::$DB->autoExecute($this->getTableName(), $insertList, self::$databaseOperation[$saveType], $where);
                }
                if (DB::isError($dbResult)) {                    
                    throw new EDatabaseError($dbResult->getMessage());
                }
            }
            
            if ($saveType == self::INSERT) {
                $this->setPk( fvSite::$DB->getLastId() );
            }
            $this->_setUnChanged();
            
            if ($logging && $this instanceof iLogger) {
                $this->putToLog(($saveType == self::INSERT)?Log::OPERATION_INSERT:Log::OPERATION_UPDATE);
            }

            return true;
        } else {
            if ($logging && $this instanceof iLogger) {
                $this->putToLog(Log::OPERATION_ERROR);
            }
            
            return false;
        }
    }    
    
    protected function saveToDatabaseLang($saveType, $logging) 
    {
        if ( $this->isValid() ) {
/*        if ( true) {*/
            $insertList = array();
            $INSERT = array();
            $langs = fvLang::getInstance()->getLangs();
            
            foreach ($this->getFieldList() as $field) 
            {                
                if( !$this->isMultiLangField($field) )
                if ($this->isChanged($field) || $this->autoValue($field, $saveType)) 
                {
                    if (in_array($this->getFieldType($field), self::$serializeTypes)) 
                    {
                         $insertList[$field] = serialize($this->get($field));
                    } else {
                        if ($this->getFieldType($field) == 'set') 
                        {
                            $insertList[$field] = implode( ',',$this->get($field) );
                        } 
                            else $insertList[$field] = $this->get($field);
                    }
                }
            }
            $INSERT[0] = $insertList;
            
            $langs = fvLang::getInstance()->getLangs();
            foreach($langs as $lang_key)
            {
                $insertList = array();
                foreach ($this->getLangFields() as $field) 
                {                

                    if ($this->isChanged($field."_".$lang_key) || $this->autoValue($field."_".$lang_key, $saveType)) 
                    {
                        if (in_array($this->getFieldType($field."_".$lang_key), self::$serializeTypes)) 
                        {
                             $insertList[$field] = serialize($this->get($field ."_".$lang_key));
                        } else {
                            if ($this->getFieldType($field) == 'set') 
                            {
                                $insertList[$field] = implode( ',',$this->get($field ."_".$lang_key) );
                            } 
                                else $insertList[$field] = $this->get($field ."_".$lang_key);
                        }
                    } elseif(!$this->get($field ."_".$lang_key)) {
                        //Запись в таблоце должна быть
                        $insertList[$field] = "";  
                    }
                    
                }
                $INSERT[$lang_key] = $insertList;
             }
            
            fvSite::$DB->autoCommit(false);
            
            foreach($INSERT as $langKeyId => $insertList)
            if (count($insertList) > 0) 
            {
                $table = ( $langKeyId === 0 ) ? $this->getTableName() : $this->getTableName()."_".$langKeyId;
                if ($saveType == self::INSERT) 
                {                        

                    if( $langKeyId === 0 )
                    {
                        $dbResult = fvSite::$DB->autoExecute($table, $insertList, self::$databaseOperation[$saveType]);
                        $LastInsertId = fvSite::$DB->getLastId();
                    } else {
                        $insertList['id'] = $LastInsertId;
                        $dbResult = fvSite::$DB->autoExecute($table, $insertList, self::$databaseOperation[$saveType]);
                    }
                } else {
                    
                    if (is_array( $keyNames = $this->getPkName() ) ) 
                    {
                        $where = '';
                         foreach ($keyNames as $key) 
                         {
                             $where .= ( ($where) ? " AND " : '' ) . $key . " = " . $this->getPk($key);
                         }
                    } else {
                        $where = $this->getPkName() . " = " . $this->getPk();
                    }
                        
                    $dbResult = fvSite::$DB->autoExecute($table, $insertList, self::$databaseOperation[$saveType], $where);                    
                }
                fvSite::$DB->commit();
                
                if (DB::isError($dbResult)) 
                {                                        
                    fvSite::$DB->rollback();
                    throw new EDatabaseError($dbResult->getMessage());
                }
            }
            
            fvSite::$DB->autoCommit(true);
            
            if ($saveType == self::INSERT) {
                $this->setPk( $LastInsertId );
            }
            $this->_setUnChanged();
            
            if ($logging && $this instanceof iLogger) {
                $this->putToLog( ($saveType == self::INSERT) ? Log::OPERATION_INSERT : Log::OPERATION_UPDATE );
            }

            return true;
        } else {
            if ($logging && $this instanceof iLogger) {
                $this->putToLog(Log::OPERATION_ERROR);
            }
            
            return false;
        }
    }
    
    function delete() {
        if ($this->isNew()) return false;
        
        $primaryKey = $this->getPkName();
        $tableName = $this->getTableName();
        
        $phc = fvSite::$DB->Prepare("DELETE FROM $tableName WHERE $primaryKey = ?");
        
        if (DB::isError(fvSite::$DB->Execute($phc, array($this->getPk())))) 
            throw new EDatabaseError("Can't delete record from database.");
        
        if ($this instanceof iLogger) {
            $this->putToLog(Log::OPERATION_DELETE);
        }
            
        return true;
    }
    
    /**
     * Fill Object by array
     *
     */
    function hydrate($MAP) {
        if (!is_array($MAP)) throw new EModelError("Can't create object from non array");
        
        foreach ($MAP as $field => $value) {
            if (isset($this->fields[$field])) {
                
                if (in_array($this->fields[$field]['type'], self::$serializeTypes) && (gettype($value) != $this->fields[$field]['type'])) {
                    $value = unserialize($value);
                }
                
                if ($this->fields[$field]['type'] == 'set' && (gettype($value) != $this->fields[$field]['type'])) {
                    $value = explode(',', $value);
                }
                                
                @settype($value, $this->fields[$field]['type']);
                
                $this->fields[$field]["value"] = $value;
                $this->fields[$field]["changed"] = false;
            }
        }
        
        
        if (is_array($this->keyName)) {
            foreach ($this->keyName as $keys) {
                if (isset($MAP[$keys])) 
                $this->key[$keys] = $MAP[$keys];
            }
        } else {
            if (isset($MAP[$this->keyName])) 
                $this->key = $MAP[$this->keyName];
        }
    
        
        $this->valid = array();
    }
    
    function toHash() {
        $result = array();
        foreach ($this->fields as $name => $values) {
            $result[$name] = $values['value'];
        }
        
        if (is_array($this->keyName)) {
            foreach ($this->keyName as $keys) {
                $result[$keys] = $this->key[$keys];
            }
        } else {
             $result[$this->keyName] = $this->key;
        }
        
        return $result;
    }
    
    public function updateFromRequest($REQUEST) {
        
        foreach ($this->fields as $fieldName => $field) {
            switch ($field['type']) 
            {
                case 'array':
                case 'set':
                    $this->fields[$fieldName]['value'] = array();
                    $this->fields[$fieldName]['changed'] = true;
                    break;
                case 'bool':
                    $this->fields[$fieldName]['value'] = false;
                    $this->fields[$fieldName]['changed'] = true;
                    break;
            }
        }
        
        foreach ($REQUEST as $field => $value) {            
            if (isset($this->fields[$field])) 
            {
                $this->set($field, $value);
            }
        }
        return $this->isValid();
    }
    
    function getPk($keyName = null) {
        if (is_null($keyName))
            return $this->key;
        else return $this->key[$keyName];
    }
    
    function setPk($key, $keyName = null) {
        if (is_null($keyName) && !is_array($this->key))
            $this->key = $key;
        else {
            $this->key[$keyName] = $key;
        }
    }
    
    function getPkName() {
        return $this->keyName;
    }

    protected function setPkName($keyName) {
        $this->keyName = $keyName;
    }
    
    function getTableName () {
        return $this->tableName;
    }
    
    protected function setTableName ($tableName) {
        $this->tableName = $tableName;
    }
    
    function isValid() {
        return (count($this->valid) == 0);
    }
    
    function getValidationResult () {
        return $this->valid;
    }
    
    protected function setValidationResult ($field, $valid, $message = "") {
        if (!$valid) $this->valid[$field] = fvSite::$fvConfig->get("entities.{$this->currentEntity}.fields.{$field}.invalid_string", $message);
        else if (isset($this->valid[$field])) unset($this->valid[$field]);
    }
    
    function autoValue ($filedName, $action) {
        if (isset($this->fields[$filedName]['auto_set']) && (strtolower($this->fields[$filedName]['auto_set']) == strtolower($action) || strtolower($this->fields[$filedName]['auto_set']) == "any")) {

            switch ($this->fields[$filedName]['type']) {
                case "integer":
                    if ($this->fields[$filedName]['auto_value'] == "inc()") $this->fields[$filedName]["value"]++;   
                    else if ($this->fields[$filedName]['auto_value'] == "dec()") $this->fields[$filedName]["value"]--;
                    else if ($this->fields[$filedName]['auto_value'] == "now()") $this->fields[$filedName]["value"] = time();
                    else $this->fields[$filedName]["value"] = intval($this->fields[$filedName]['auto_value']);
                    break;
                case "date":
                    if ($this->fields[$filedName]['auto_value'] == "now()") $this->fields[$filedName]["value"] = date("Y-m-d H:i:s");
                    else $this->fields[$filedName]["value"] = $this->fields[$filedName]['auto_value'];
                    break;
                case "datetime":
                    if ($this->fields[$filedName]['auto_value'] == "now()") $this->fields[$filedName]["value"] = date("Y-m-d H:i:s");
                    else $this->fields[$filedName]["value"] = $this->fields[$filedName]['auto_value'];
                    break;                    
                case "bool":
                    $this->fields[$filedName]["value"] = (bool)$this->fields[$filedName]['auto_value'];
                    break;
                case "string":
                    $this->fields[$filedName]["value"] = (string)$this->fields[$filedName]['auto_value'];
                    break;
            }

            return true;
        }
        
        return false;
    }

    function isNew() {
        return empty($this->key);
    }

    function hasField($fieldName) {
        return isset($this->fields[$fieldName]);
    }
    
    function getFieldType($fieldName) {
        return $this->fields[$fieldName]['type'];
    }
    
    function offsetExists($fieldName) {
        return $this->hasField($fieldName);
    }

    function offsetGet($fieldName) {
        return $this->get($fieldName, null);
    }

    function offsetUnset($fieldName) {
        return $this->removeField($fieldName);
    }
    
    function offsetSet($fieldName, $newValue) {
        if ($this->hasField($fieldName))
            return $this->set($fieldName, $newValue);
        else $this->addField($fieldName, ($newValue)?gettype($newValue):'string', $newValue);
    }
    
    function getDictionary() 
    {
        $args = func_get_args ();
        $where = $order = $limit = "";
        $func_option = '*';
        $field = 'id';
        $field_search = 'id';
        $table = $this->tableName;
        $params = array ();
        
        if (! empty ( $args [0] ))
            $func_option = $args [0];
        if (! empty ( $args [1] ))
            $table = $args [1];
        if (! empty ( $args [2] ))
            $field = $args [2];
        if (! empty ( $args [3] ))
            $field_search = $args [3];
        
        $query = "SELECT $func_option FROM " . $table . " WHERE $field_search=" . $this->$field;
        $res = fvSite::$DB->query ( $query, $params );
        $result = array ();
        if (! DB::isError ( $res )) {
            while ( $row = $res->fetchRow () ) {
                $result [] = $row;
            }
        }
        //        var_dump($result);
        if ($result)
            return $result [0] [$func_option];
        else
            return '�������� �� ����������';
    }
    
    public function filterData($document)
    {
        $search = array ('@<script[^>]*?>.*?</script>@si', // Strip out javascript
             '@<[\/\!]*?[^<>]*?>@si',          // Strip out HTML tags
             '@([\r\n])[\s]+@',                // Strip out white space
             '@&(quot|#34);@i',                // Replace HTML entities
             '@&(amp|#38);@i',
             '@&(lt|#60);@i',
             '@&(gt|#62);@i',
             '@&(nbsp|#160);@i',
             '@&(iexcl|#161);@i',
             '@&(cent|#162);@i',
             '@&(pound|#163);@i',
             '@&(copy|#169);@i',
             '@&#(\d+);@e');                    // evaluate as php

        $replace = array ('',
              '',
              '\1',
              '"',
              '&',
              '<',
              '>',
              ' ',
              chr(161),
              chr(162),
              chr(163),
              chr(169),
              'chr(\1)');
              
        return str_replace('"','', preg_replace($search, $replace, $document));       
    }
    

        /**
        * Проверка валидности email
        * 
        * @param string $value
        */
        public function doValidateEmail($value)
        {
            return (preg_match("/^[a-z0-9_\-\.]+@[a-z0-9_\-\.]+\.[a-z]{2,3}$/i", $value) > 0);
        }

        /**
        * Проверка валидности на пустоту
        * 
        * @param string $value
        * @param int $length - минимальная длинна строка
        */
        public function doValidateEmpty($value,$length = 0)
        {
            $value = trim($value);
            return  (strlen( $value ) > $length);
        }

        /**
        * Проверка валидности на латиницу A-Za-z0-9
        * 
        * @param string $value    
        */
        public function doValidateLatin($value)
        {
            return  (preg_match("/^[a-zA-Z0-9]{1,}$/i", $value) > 0);
        }

        /**
        * Проверка валидности на уникальность
        * 
        * @param string $value значение    
        * @param string $field имя проверяемого поля        
        */
        public function doValidateUniq($value,$field)
        {    
            return ($this->getManager()->getCount("{$field}='{$value}'") == 0);        
        }
        /**
        * Проверить наличие записи в справочнике
        * 
        * @param int $id первичный ключ
        * @param fvRootManager $dict справочник
        */
        public function doValidateDict($id,$dict)
        {
            return $dict->isRootInstance($dict->getByPk($id));
        }            
        /**
        * Получить имя текущей сущности
        * 
        */
        public function getCurrentEntity()
        {
            return $this->currentEntity;    
        }
        
        public function isMultiLangEntity()
        {
            foreach((array)$this->fields as $field)
                if((bool)$field['multilang'] == true)
                    return true;
            return false;
        }
        
        private function doChangeMultiLang()
        {
            if(!$this->isMultiLangEntity())
                return;
            $this->doCheckMultiLangTable();
            $langs = fvLang::getInstance()->getLangs();
            foreach($this->fields as $field => $fieldParam)
            {
                if($fieldParam['multilang'] == true)
                {
                    //$this->removeField($field);
                    foreach($langs as $lang_key)
                        $this->addField($field."_".$lang_key, $fieldParam['type'], null , $fieldParam['validate']);
                }
            }
        }
        
        private function doCheckMultiLangTable()
        {
            $langs = fvLang::getInstance()->getLangs();
            foreach($langs as $lang_key)
            {
                try{                            
                    $sql = "SELECT 1 FROM " .$this->getTableName() ."_".$lang_key;
                    @$result = fvSite::$DB->query($sql);
                    if(DB::isError($result))
                        throw new Exception(true);
                     
                } catch (Exception $db) {
                    $this->createLangTableSQL($lang_key);
                    $this->createLangViewSQL($lang_key);
                }
            }            
        }
        
        private function createLangViewSQL ($lang)
        {
            $currentFields = $this->fields;
            
            $langFields = $this->getLangFields();
            $langSelect = array();
            $currentSelect = array();
            foreach($langFields as $langField)
            {
                $langSelect[] = "t2.".$langField;
                unset($currentFields[$langField]);
            }
            foreach($currentFields as $currentField => $currentFieldParam)
                $currentSelect[] = "t1.".$currentField;
            $sql = "CREATE ALGORITHM = MERGE VIEW `{$this->getClearTableName()}_view_{$lang}` 
                    AS 
                    SELECT t1.id, " . (count($currentSelect) > 0 ? implode(", ", $currentSelect) . ", " : "") 
                                    . implode(", ", $langSelect) . " 
                    FROM {$this->getTableName()} as t1 
                    LEFT JOIN {$this->getTableName()}_{$lang} as t2 ON t1.id = t2.id ; ";
            
            $result = fvSite::$DB->query($sql); 
        }
        
        private function createLangTableSQL($lang)
        {
            
            $fields = array();    
            $FK_KEY_STRING = "FK_" . $this->getTableName() . "_";
            foreach($this->fields as $fieldName => $fieldParam)
            {
                if($fieldParam['multilang'] == true )
                {
                    $techParamField = $this->getTechInfoTable($fieldName);
                    $techParamString = $techParamField['DATA_TYPE'];
                    $techParamString .= $techParamField['CHARACTER_MAXIMUM_LENGTH'] ? " (" .$techParamField['CHARACTER_MAXIMUM_LENGTH'] . " ) " : ""; 
                    $techParamString .= $techParamField['IS_NULLABLE'] == "NO" ? " NOT NULL " : " NULL "; 
                    $fields[] = "`".$fieldName."` " . $techParamString ;
                    $FK_KEY_STRING .= $fieldName ."_";
                }
            }                       
            
            $sql = "CREATE TABLE `{$techParamField['TABLE_NAME']}_{$lang}` (
                    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT, ";
                    foreach($fields as $createFieldRow)
            $sql .= $createFieldRow .", " .PHP_EOL;                                    
            $sql .="PRIMARY KEY (`id`),
                    CONSTRAINT `{$FK_KEY_STRING}_{$lang}` FOREIGN KEY (`id`) REFERENCES `{$techParamField['TABLE_NAME']}` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB
            ROW_FORMAT=DEFAULT";
            $result = fvSite::$DB->query($sql); 
        }
        
        private function getTechInfoTable($column)
        {
            $sql = "SELECT c.ORDINAL_POSITION as id, c.* FROM `information_schema`.COLUMNS as c
                    WHERE c.TABLE_SCHEMA = '".fvSite::$fvConfig->get('database.name')."'
                    AND c.TABLE_NAME = '". $this->getClearTableName() . "'
                    AND c.COLUMN_NAME = '{$column}'";
            $array = (array)fvSite::$DB->getAssoc($sql);
            return current ( $array );
            
        }
        
        public function getLangFields($full = false)
        {
            $out = array();
            foreach($this->fields as $fieldName => $fieldParam)
                if((bool)$fieldParam['multilang'] == true)
                    if($full) $out[$fieldName] = $fieldParam;
                    else $out[] = $fieldName;
            return $out;
        }
        
        public function getNoLangFields()
        {
            $curFields = $this->fields;
            foreach($this->getLangFields() as $lField)
                unset($curFields[$lField]);
            return array_keys($curFields);
        }
        
        private function getClearTableName()
        {
            return str_replace(fvSite::$fvConfig->get('database.name') . ".", "", $this->getTableName());
        }
        
        public function isMultiLangField( $field, $only_schema = false )
        {
            if(!$only_schema)
            {
                $langs = fvLang::getInstance()->getLangs();
                foreach($langs as $lang_key)
                    if(strpos($field, "_".$lang_key) && isset($this->fields[$field]))
                        return true;
            }
            return (bool)$this->fields[$field]['multilang'];
        }
        
        public function getLang($field, $lang = false)
        {
            $lang = $lang ? $lang : fvLang::getInstance()->getCurLang();
            $default = fvLang::getInstance()->getDefaultLang();
            $translate = strlen( $this->get($field."_".$lang) ) ? $this->get($field."_".$lang) : $this->get($field."_".$default);
            return $translate;
        }         
        
        /**
        * @param mixed $onlyClassName
        * @return fvRootManager
        */
        function getManager($onlyClassName = false)
        {
            $className = $this->currentEntity . "Manager";
            if ($onlyClassName === false) 
                return call_user_func(array($className,"getInstance"));
            return $className;
        }       
        
        function setNULL($fieldName) {
            if (isset($this->fields[$fieldName])) { 

                $query = "UPDATE {$this->tableName} SET {$fieldName} = NULL WHERE id = {$this->getPk()}";
                fvSite::$DB->query($query);

            }
            else throw new EFieldError("No field '{$fieldName}' is specified");

            return true;
        }  
        
        function _getDictFieldBy($property, $type, $expr, $field = false)
        {                  
            $propExists = property_exists($this,$property);

            if ($propExists && $this->$property)
                return $this->$property;        
            if (is_array($expr)) 
                $expr = implode(" and ", $expr);                         

                
            $element = $this->getManager()->_getDictElement($type, $expr, $field);
            if (!$propExists) 
                $this->addField($property,gettype($element),$element);
                
            return $this->$property;
        }
}
