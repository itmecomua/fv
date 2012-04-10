<?php

require_once(fvSite::$fvConfig->get("path.entity") . "fvroot.class.php");

/**
 *
 */
abstract class fvRootManager extends fvDebug{

    protected $rootObj = null;
    static $instace = null;

    const GET_EQUAL = '=';
    const GET_NOT_EQUAL = '<>';
    const GET_GT = '>';
    const GET_GTE = '>=';
    const GET_LT = '<';
    const GET_LTE = '<=';
    const GET_LIKE = 'LIKE';
    const GET_NOT_LIKE = 'NOT LIKE';

    const GET_CHECK_CASE = 'cc';
    const GET_UNCHECK_CASE = 'ucc';

    /**
     *
     */
    protected function __construct () {
    }

    abstract static function getInstance();

    /**
    * @return fvRoot
    * @param mixed $pk
    * @param mixed $newInst
    */
    public function getByPk ($pk, $newInst = false) {

        if ( $this->isMultiLangEntity() )
            return $this->getByPkLang($pk, $newInst);
        $primaryKey = $this->rootObj->getPkName();
        $tableName = $this->rootObj->getTableName();

        $where = '';
        $params = array();
        if (is_array($primaryKey)) {
            if (!is_array($pk)) return false;

            foreach ($primaryKey as $key => $pkName) {
                $where .= (($where)?' AND ':'') . "$pkName = ?";
                if (array_key_exists($pkName, $pk)) {
                    $params[] = $pk[$pkName];
                } else {
                    if (!isset($pk[$key])) return false;
                    $params[] = $pk[$key];
                }
            }
        } else {
            $where = "$primaryKey = ?";
            $params = array($pk);
        }

        $data = fvSite::$DB->getRow("SELECT * FROM $tableName WHERE $where", $params);

        if (fvSite::$DB->lastSelectCount) {
            $o = clone $this->rootObj;
            $o->hydrate($data);

            return $o;
        }

        return ($newInst) ? $this->cloneRootInstance() : false;
    }    
    
    /**
    * @return fvRoot
    * @param mixed $pk
    * @param mixed $newInst
    */
    public function getByPkLang ($pk, $newInst = false) 
    {

        $primaryKey = $this->rootObj->getPkName();
        $tableName = $this->rootObj->getTableName();

        $where = '';
        $params = array();
        if (is_array($primaryKey)) {
            if (!is_array($pk)) return false;

            foreach ($primaryKey as $key => $pkName) {
                $where .= (($where)?' AND ':'') . "$pkName = ?";
                if (array_key_exists($pkName, $pk)) {
                    $params[] = $pk[$pkName];
                } else {
                    if (!isset($pk[$key])) return false;
                    $params[] = $pk[$key];
                }
            }
        } else {
            $where = "$primaryKey = ?";
            $params = array($pk);
        }

        $langs = fvLang::getInstance()->getLangs();
         
        $DATAS = array();
        foreach($langs as $lang)   
        {
            $data = fvSite::$DB->getRow("SELECT * FROM {$tableName}_view_{$lang} WHERE $where", $params);
            foreach($this->rootObj->getLangFields() as $langField)
            {
                if(is_array($data) && array_key_exists($langField, $data))
                {
                    $data[$langField."_".$lang] = $data[$langField];
                    //unset($data[$langField]);
                }
            }
            if(is_array( $data ) )
                $DATAS = array_merge($DATAS, $data);
        }
        
        
        if (fvSite::$DB->lastSelectCount) {
            $o = clone $this->rootObj;
            $o->hydrate($DATAS);

            return $o;
        }

        return ($newInst) ? $this->cloneRootInstance() : false;
    }

    public function getAssoc($sql)
    {
        $arrResult = array();
        $result = fvSite::$DB->query($sql);
        while($row = mysql_fetch_assoc($result->result))
        {
            $arrResult[] = $row;
        }

        return $arrResult;
    }
    
    function getAll() {
        $args = func_get_args();
        if($this->isMultiLangEntity())
            return $this->getAllLang($args);        

        $where = $order = $limit = "";
        $params = array();

        if (!empty($args[0])) $where = "WHERE (" . $args[0] . ")";
        if (!empty($args[1])) $order = "ORDER BY " . $args[1] . "";
        if (!empty($args[2])) $limit = explode(',', $args[2]);
        if (isset($args[3]))  $params = (is_array(@$args[3]))?@$args[3]:array(@$args[3]);
        
        $query = "SELECT * FROM " . $this->rootObj->getTableName() . " $where $order";                                         

        if (is_array($limit) && count($limit) == 2) {
            $res = fvSite::$DB->limitQuery($query, $limit[0], $limit[1], $params);
        }
        else {
            $res = fvSite::$DB->query($query, $params);
        }

        $result = array();

        if (!DB::isError($res)) {
            while ($row = $res->fetchRow()) {
                $o = clone $this->rootObj;
                $o->hydrate($row);

                $result[] = $o;
            }
        }

        return $result;
    }
    
    public function getAllLang()
    {
        $args = current( (array)func_get_args() );

        $where = $order = $limit = "";
        $params = array();

        if (!empty($args[0])) $where = "WHERE (" . $args[0] . ")";
        if (!empty($args[1])) $order = "ORDER BY " . $args[1] . "";
        if (!empty($args[2])) $limit = explode(',', $args[2]);
        if (isset($args[3]))  $params = (is_array(@$args[3]))?@$args[3]:array(@$args[3]);

        $langs = fvLang::getInstance()->getLangs();
        
        $fields = array(); 
        foreach($langs as $lang_key)
        {
            foreach( $this->rootObj->getLangFields() as $lField)
            {
                $fields[] = "t_{$lang_key}.{$lField} as {$lField}_{$lang_key}";
            }
        }
        $fields = (count($fields)) ?  ",".implode(",", $fields) : "";
        $query = "SELECT t1.* {$fields} FROM " . $this->rootObj->getTableName() ." as t1";
        
        
        foreach($langs as $lang_key)
        {
            $query .= " LEFT JOIN  " . $this->rootObj->getTableName() ."_".$lang_key ." as t_{$lang_key} ON t_{$lang_key}.id = t1.id";
        }
        
        $query .= " {$where} {$order}";
        

        if (is_array($limit) && count($limit) == 2) {
            $res = fvSite::$DB->limitQuery($query, $limit[0], $limit[1], $params);
        }
        else {
            $res = fvSite::$DB->query($query, $params);
        }

        $result = array();

        if (!DB::isError($res)) {
            while ($row = $res->fetchRow()) {
                $o = clone $this->rootObj;
                $o->hydrate($row);

                $result[] = $o;
            }
        }

        return $result;        
    }
  

    function htmlSelect ($field, $empty = "", $where = null, $order = null, $limit = null, $args = array()) {
        $objs = $this->getAll($where, $order, $limit, $args);

        $result = array();
        if (!empty($empty)) {
            $result[''] = $empty;
        }

        foreach ($objs as $obj)
        {
            $result[$obj->getPk()] = $obj->$field;
        }

        return $result;
    }

    public function getCount() {
        $args = func_get_args();

        $where = "";
        $params = array();

        if (!empty($args[0])) $where = "WHERE ({$args[0]})";
        if (!empty($args[1])) $params = (is_array($args[1]))?$args[1]:array($args[1]);
        $query = "SELECT count(*) as cnt FROM " . $this->rootObj->getTableName() . " $where";

        $count = fvSite::$DB->getOne($query, $params);

        if (fvSite::$DB->lastSelectCount) {
            return $count;
        }

        return false;
    }

    public function __call($name, $arguments) {
        if (strpos($name, 'getBy') === 0) {
            if (($fieldName = $this->checkName(substr($name, 5))) === false) {
                throw new EManagerError("Can't recognozed filed '" . substr($name, 5) . "'");
            }
        } elseif (strpos($name, 'getOneBy') === 0) {
            if (($fieldName = $this->checkName(substr($name, 8))) === false) {
                throw new EManagerError("Can't recognozed filed '" . substr($name, 8) . "'");
            }
        } else {
            throw new EManagerError("Call to undefined function");
        }

        $condition = (!empty($arguments[1]))?$arguments[1]:self::GET_EQUAL;
        if (($cc = (!empty($arguments[2]))?($arguments[2] == self::GET_UNCHECK_CASE):true) === false) {
            $value = strtoupper($arguments[0]);
        } else {
            $value = $arguments[0];
        }

        if ($cc) {
            $where = "{$fieldName} {$condition} ?";
        } else {
            $where = "UPPER({$fieldName}) {$condition} ?";
        }

        if (strpos($name, 'getBy') === 0) {
            return $this->getAll($where, null, null, array($value));
        } else {
            $object = $this->getAll($where, null, null, array($value));
            if (is_object($object[0])) return $object[0];
            else return false;
        }

    }

    protected function checkName($name) {
        if ($this->rootObj->hasField($name)) return $name;

        for ($i = 1; $i < strlen($name); $i++) {
            if ($name{$i} == strtoupper($name{$i})) {
                $name = substr($name, 0, $i) . "_" . strtolower($name{$i}) . substr($name, $i + 1);
                $i++;
            }
        }
        $name = strtolower($name);

        if ($this->rootObj->hasField($name)) return $name;
        return false;
    }

    public function massUpdate($where, $updateFields) {

        $o = clone $this->rootObj;

        foreach ($updateFields as $field => $value) {
            $o[$field] = $value;
        }

        $insertList = array();
        foreach ($o->getFieldList() as $field) {
            if ($o->isChanged($field) || $o->autoValue($field, $saveType)) {
                if (in_array($o->getFieldType($field), fvRoot::getSerializedTypes())) {
                    $insertList[$field] = serialize($o->get($field));
                }
                else $insertList[$field] = $o->get($field);
            }
        }

        try {
            $dbResult = fvSite::$DB->autoExecute($o->getTableName(), $insertList, DB_AUTOQUERY_UPDATE, $where);
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }

        if (DB::isError($dbResult)) {
            throw new EDatabaseError($dbResult->getMessage());
        }

        return true;
    }
    
        
    public function getObjectBySQL($sql,$addField = array(),$single_object = false)
    {
        $data = $this->getAssoc($sql);
        $res = array();
        foreach($data as $k=>$v)
        {
            $ex = new $this->rootObj;
            if(count($addField))
            {
                foreach($addField as $key=>$val)
                {
                    $ex->addField($key,"$val","");
                }
            }
            $ex->hydrate($v);
            $res[] = $ex;
        }
        if($single_object)
        {
            if(isset($res[0]))
                return $res[0];
            else return array();
        }   
        else
            return $res;
    }
    
    
    function getTableName()
    {
        return $this->rootObj->getTableName();
    }
    
    function _getDictElement($inst, $expr, $field)
    {                   
        if (is_object($inst))
            $type = $inst->getTableName();
        else {
            $type = $inst;
            $inst = call_user_func(array($type . "Manager","getInstance")); 
        }        
        if ( ($dict = $this->_getDict($type,$expr)) )
            return ($field ? $dict->$field : $dict);                                
        
        if ((int)$expr) 
        {                                    
            $inst = $inst->getByPk($expr);                    
        }
        else 
        {
            $inst = $inst->getAll($expr);
            list($inst) = $inst;                        
        }
    
        if(!$inst) return false;
         
        if ($field) {            
            return $this->_addDict($type, $expr, $inst)->$field;              
        }
        
        return $this->_addDict($type, $expr, $inst);
    }
    function _getDict ($type = false, $expr = false)
    {                        
        if ($type && $expr)
          return $this->_dict[$type][$expr];        
        return $this->_dict;
    }
    function _addDict ($type, $expr, $inst)
    {                                           
        return ($this->_dict[$type][$expr] = $inst);
    }
    public function getCreateTable()
    {         
        return fvSite::$DB->getAssoc("SHOW CREATE TABLE {$this->getTableName()};");
    }                     
    
     public function isExistsTable($tableName)
    {
        $sql = "select 1 from {$tableName} limit 1";
        
        $result = @fvSite::$DB->query($sql);        
        if(DB::isError($result))
        {
            return false;
        }
        return true;
        
    }    
    public function isRootInstance($inst)
    {     
        return is_a( $inst, get_class($this->rootObj) );
    }
    
    /**
    * @return fvRoot
    */
    public function cloneRootInstance()
    {     
        return clone $this->rootObj;
    }
    public function massDeleteSimple($where = false) 
    {
        if ($where && is_array($where)) {
            $where = implode(" and ", $where);
        }
        $where = $where ? " where " . $where : ""; 
        $sql = "delete from {$this->getTableName()}  " . $where;
                         
        fvSite::$DB->query($sql);
        if (DB::isError($dbResult)) 
            return false;
        return true;
    }
    public function getConst($constName)
    {     
        eval("\$const = {$this->rootObj->getManager(true)}::{$constName};");
        return $const;
    }
    
    public function isMultiLangEntity()
    {
        foreach($this->rootObj->getFields() as $field)
            if((bool)$field['multilang'] == true)
                return true;
        return false;
    }
}
