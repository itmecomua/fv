<?php

require_once(fvSite::$fvConfig->get("path.entity") . "fvroot.class.php");

abstract class fvUploaded extends fvRoot {
    
    const IMAGE_TYPE_NORMAL     = 'normal';
	const IMAGE_TYPE_THUMB      = 'thumb';
	const IMAGE_TYPE_SMALL      = 'small';
	const IMAGE_TYPE_SMALLONE   = 'smallone';
  	const IMAGE_TYPE_SMALLTWO   = 'smalltwo';

	const IMAGE_TYPE_LARGE      = 'large';

	const IMAGE_TYPE_CRBCENTER  = 'crbcenter';
	const IMAGE_TYPE_CRBRIGHT   = 'crbright';
	const IMAGE_TYPE_CRBLEFT    = 'crbleft';
	
	const IMAGE_WIDTH = 0;
	const IMAGE_HEIGHT = 1;
	const IMAGE_DEMENTIONS = 3;
	
	function __construct($fields, $tableName, $keyName = "id") {
        parent::__construct($fields, $tableName, $keyName);    	
	}
	
	public function hasFile ($fieldName, $size = null) {
	    if (is_null($size))
	       return is_file($this->getImageDir().$this[$fieldName]);
	    else return is_file($this->getImageDir().$this->getImageName($fieldName, $size));
	}
	
	public function getImageSize($fieldName, $size = self::IMAGE_TYPE_NORMAL, $idx = self::IMAGE_DEMENTIONS) {
	    $info = getimagesize($this->getImageDir().$this->getImageName($fieldName, $size));
	    return $info[$idx];
	}
	
	public function getImageDir($web = false) {
		$baseDir = '';
		if ($web)
			$baseDir = fvSite::$fvConfig->get('path.upload.web_root_dir');
		else $baseDir = fvSite::$fvConfig->get('path.upload.root_dir');
		$currentDir = $baseDir . fvSite::$fvConfig->get('path.entImages.'.$this->currentEntity) . str_pad($this->getPk(), 8, '0', STR_PAD_LEFT) . "/";

		if (!$web && !$this->isNew() && !is_dir($currentDir)) mkdir($currentDir, 0777);
		
		return $currentDir;
	}
	
	public function deleteImages($fieldName) {
	    $imagePattern = $this[$fieldName];
	    
	    foreach (glob ($this->getImageDir() . "*$imagePattern") as $fileName) {
	        unlink($fileName);
	    }
	    
	    $this[$this->getImageField()] = '';
	    $this->save();
	}
	
	public function getImageName($fieldName, $size = self::IMAGE_TYPE_NORMAL) {
	   return $size . "_" . $this->getOrigFileName($fieldName);
	}

	public function getOrigFileName ($fieldName) {
	    return $this[$fieldName];
	}
	
    public function saveUploadFile($fieldName, $origFile) {
        $info = pathinfo($origFile);
        $fileName = $info['basename'];
        
        $this[$fieldName] = $fileName;

        rename($origFile, $this->getImageDir() . $fileName);
    }
    public static function getSelfConst($constName)
    {     
            eval("\$const = self::{$constName};");
            return $const;
    }
}

