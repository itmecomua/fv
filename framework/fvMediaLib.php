<?php

class fvMediaLib {
    
    const THUMB_WIDTH = 1;
    const THUMB_SQUADRE = 2;
    
    /**
     * Method to create Image Thumbnail (use gd library) allowed Images - image/gif, image/jped and image/png
     *
     * @param string $srcFileName - sourse Image Name
     * @param string $destFileName - destination Image Name
     * @param array $params
     */
    
    public static function createThumbnail ($srcFileName, $destFileName, $params = array()) {
        
        $allowedTypes = array('IMAGETYPE_GIF', 'IMAGETYPE_JPEG', 'IMAGETYPE_PNG', 'IMAGETYPE_SWF', 'IMAGETYPE_PSD', 
        		'IMAGETYPE_BMP', 'IMAGETYPE_TIFF_II', 'IMAGETYPE_TIFF_MM', 'IMAGETYPE_JPC', 'IMAGETYPE_JP2', 
        		'IMAGETYPE_JPX', 'IMAGETYPE_JB2', 'IMAGETYPE_SWC', 'IMAGETYPE_IFF', 'IMAGETYPE_WBMP', 'IMAGETYPE_XBM', 'IMAGETYPE_ICO');
        
        $default_type = fvSite::$fvConfig->get('images.default_type', 'normal');
        
        if (!empty($params['type'])) $default_type = $params['type'];
        
        if (!empty($params['width'])) $width = $params['width']; else $width = (int)fvSite::$fvConfig->get("images.{$default_type}.width"); 
        if (!empty($params['height'])) $height = $params['height']; else $height = (int)fvSite::$fvConfig->get("images.{$default_type}.height");
        if (!empty($params['resize_type'])) $type = $params['resize_type']; else $type = (int)fvSite::$fvConfig->get("images.{$default_type}.type");
        
        list($orig_width, $orig_height, $orig_type) = getimagesize($srcFileName);
        
        switch ($type) {
            case self::THUMB_WIDTH:
                if ($orig_width > $width) {
                    $ratio = ($width / $orig_width);
                    $height = round($orig_height * $ratio);
                }
                else {
                    $width = $orig_width;
                    $height = $orig_height;
                }
            break;
            case self::THUMB_SQUADRE:
                
                if ($width > $height) {
                    $val = "width";
                    $aval = "height";
                }
                else {
                    $val = "height";
                    $aval = "width";
                }
                
                if (${'orig_' . $val} > ${$val}) {
                    $ratio = (${$val} / ${'orig_' . $val});
                    ${$aval} = round(${'orig_' . $aval} * $ratio);
                }
                else {
                    $width = $orig_width;
                    $height = $orig_height;
                }
            break;
            default:
                return false;
                break;
        }
        
        $origFileExt = '';
        
        if ($width == $orig_width && $height = $orig_height) {
            copy($srcFileName, $destFileName);
            return true;
        }
        
        foreach ($allowedTypes as $allowedType) {
            if (defined($allowedType) && (constant($allowedType) == $orig_type)) {
                $origFileExt = strtolower(substr($allowedType, strpos($allowedType, "_") + 1));
            }
        }
        
        if (!function_exists($functionName = "imagecreatefrom" . $origFileExt)) {
            return false;
        }
        
        if (($srcImage = call_user_func($functionName, $srcFileName)) === false) return false;
        if (($dstImage = imagecreatetruecolor($width, $height)) === false) return false;
        
        imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $width, $height, $orig_width, $orig_height);
        
        if (!function_exists($functionName = "image" . $origFileExt)) {
            return false;
        }
        
//        header("Content-Type: " . image_type_to_mime_type($orig_type));
        if (call_user_func($functionName, $dstImage, $destFileName) === false) return false;

        imagedestroy($srcImage);
        imagedestroy($dstImage);
        
        return true;
    }
    
    public static function calcDementions($srcFileName, $params=array()) {
        if (!empty($params['type'])) $default_type = $params['type']; else $default_type = fvSite::$fvConfig->get('images.default_type', 'normal');
        if (!empty($params['width'])) $width = $params['width']; else $width = (int)fvSite::$fvConfig->get("images.{$default_type}.width"); 
        if (!empty($params['height'])) $height = $params['height']; else $height = (int)fvSite::$fvConfig->get("images.{$default_type}.height");
        if (!empty($params['resize_type'])) $type = $params['resize_type']; else $type = (int)fvSite::$fvConfig->get("images.{$default_type}.type");
        
        list($orig_width, $orig_height, $orig_type) = getimagesize($srcFileName);
        switch ($type) {
            case self::THUMB_WIDTH:
                if ($orig_width > $width) {
                    $ratio = ($width / $orig_width);
                    $height = (int)round($orig_height * $ratio);
                }
                else {
                    $width = $orig_width;
                    $height = $orig_height;
                }
            break;
            case self::THUMB_SQUADRE:
                
                if ($width > $height) {
                    $val = "width";
                    $aval = "height";
                }
                else {
                    $val = "height";
                    $aval = "width";
                }
                
                if (${'orig_' . $val} > ${$val}) {
                    $ratio = (${$val} / ${'orig_' . $val});
                    ${$aval} = (int)round(${'orig_' . $aval} * $ratio);
                }
                else {
                    $width = $orig_width;
                    $height = $orig_height;
                }
            break;
            default:
                return false;
                break;
        }
        
        return array($width, $height, $orig_type, "width=\"$width\" height=\"$height\"");
    }
}

?>
