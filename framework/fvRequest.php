<?php
class fvRequest {
/*
    protected $method;
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const HEAD = 'HEAD';
    const ERROR_FILE_SIZE = 1;
    const ERROR_FILE_TYPE = 2;
    const ERROR_SUCCESS = 0;
    protected $escapeMethod;

    public function __construct () {
        $this->method       = $_SERVER['REQUEST_METHOD'];
        $this->escapeMethod = "htmlspecialchars";
    }
*/
    public function getRequestUrl() {
        return trim( fvSite::getConfig()->get('requestUrlSource') , "/");
    }
    
     public function getRequestUrlparts() {
        return explode( "/" , $this->getRequestUrl()  );
    }

/*
    public function getRequestMethod() {
        return $this->method;
    }

    public function setEscapeMethod($name) {
        if (function_exists($name)) {
            $this->escapeMethod = $name;
        }
    }
	
	public function getVideoSize($fileName)
	{
		$ffmpegInstance = new ffmpeg_movie($fileName);
		$video_size['width'] = $ffmpegInstance->getFrameWidth();
		$video_size['height'] = $ffmpegInstance->getFrameHeight();
		$video_size['duration'] = $ffmpegInstance->getDuration();
		$video_size['frame_rate'] = $ffmpegInstance->getFrameRate();
		$video_size['frame_count'] = $ffmpegInstance->getFrameCount();
		return $video_size;
	}
    
    public function getEscapedParameter($name, $type = null, $default = null) {
    	return call_user_func($this->escapeMethod, $this->getRequestParameter($name, $type, $default));
    }

    public function getRequestParameter($name, $type = null, $default = null) {
        $return = null;
        if (!empty($_REQUEST[$name])) $return = $_REQUEST[$name];
        else $return = $default;

        if (!is_null($type)) {
            settype($return, $type);
        }

        return $return;
    }

    public function putRequestParameter($name, $value) {
        $_REQUEST[$name] = $value;
    }

    public function hasRequestParameter($name) {
        return isset($_REQUEST[$name]);
    }

    public function isXmlHttpRequest() {
        return ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
    }

    public function uploadCount($fileName) {
        if (is_array(is_array($_FILES[$fileName]['tmp_name']))) return count($_FILES[$fileName]['tmp_name']);
        return 1;
    }

    public function isFileUpload($fileName, $idx = 0) {
        if (is_array($_FILES[$fileName]['tmp_name']))
            return is_uploaded_file($_FILES[$fileName]['tmp_name'][$idx]);
        else return is_uploaded_file($_FILES[$fileName]['tmp_name']);
    }

    public function getUploadFileType ($fileName, $idx = 0) {
        if (is_array($_FILES[$fileName]['type']))
            return $_FILES[$fileName]['type'][$idx];
        else return $_FILES[$fileName]['type'];
    }

    public function getUploadFileSize ($fileName, $idx = 0) {
        if (is_array($_FILES[$fileName]['size']))
            return $_FILES[$fileName]['size'][$idx];
        else return $_FILES[$fileName]['size'];
    }

    public function getUploadFileData ($fileName, $idx = 0) {
        $realFileName = null;

        if (is_array($_FILES[$fileName]['name']))
            $realFileName = $_FILES[$fileName]['name'][$idx];
        else $realFileName = $_FILES[$fileName]['name'];

        return array(
            'file_name'	=> substr($realFileName, 0, strrpos($realFileName, ".")),
        	'file_ext'	=> substr($realFileName, strrpos($realFileName, ".") + 1),
        );
    }

    public function getUploadTmpName($fileName, $idx = 0) {
        if (is_array($_FILES[$fileName]['tmp_name']))
            return $_FILES[$fileName]['tmp_name'][$idx];
        else return $_FILES[$fileName]['tmp_name'];
    }

    public function getUploadFileName($fileName, $idx = 0) {
        if (is_array($_FILES[$fileName]['name']))
            return $_FILES[$fileName]['name'][$idx];
        else return $_FILES[$fileName]['name'];
    }

    public function checkUploadFile($fileName, $idx = 0) {
        $file_type = $this->getUploadFileType($fileName, $idx);
        $file_data = $this->getUploadFileData($fileName, $idx);
        $file_size = $this->getUploadFileSize($fileName, $idx);

        if ($file_size > fvSite::$fvConfig->get("upload.allowed_filesize")) {
            return self::ERROR_FILE_SIZE;
        }

        if (is_array($allowed_types = fvSite::$fvConfig->get("upload.allowed_types"))) {
            if (!in_array(strtolower($file_type), $allowed_types))
                return self::ERROR_FILE_TYPE;
        } else if (is_array($allowed_ext = fvSite::$fvConfig->get("upload.allowed_ext"))) {
            if (!in_array(strtolower($file_data['file_ext']), $allowed_ext))
                return self::ERROR_FILE_TYPE;
        }

        return self::ERROR_SUCCESS;
    }

    public function saveUploadData($fileName, $destination, $idx = 0) {
         move_uploaded_file($this->getUploadTmpName($fileName, $idx), $destination);
    }

    public function parseQueryString($query, $param, $value = null) 
    {
        list($url, $params) = explode('?', $query);

        $found = false;
        $result = '';
        foreach (explode("&", $params) as $paramPare) {
            if ($paramPare === "") continue;
            list ($_key, $_value) = explode('=', $paramPare);

            if ($_key == $param) {
                $found = true;
                if ($value !== null) {
                    $result .= (($result)?"&":"") . "$_key=$value";
                }
            } else {
                $result .= (($result)?"&":"") . "$_key=$_value";
            }
        }

        if (!$found) {
            $result .= (($result)?"&":"") . "$param=$value";
        }

        return $url . (($result)?"?$result":'');
    }
*/    
}