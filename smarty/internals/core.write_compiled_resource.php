<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * write the compiled resource
 *
 * @param string $compile_path
 * @param string $compiled_content
 * @return true
 */
 function myftpchmod($file)
    {
        $ftp_server = fvSite::$fvConfig->get("ftp.server_name");
        $ftp_user_name=fvSite::$fvConfig->get("ftp.user");
        $ftp_user_pass=fvSite::$fvConfig->get("ftp.pass");
        $file = $file;
        $conn_id = ftp_connect($ftp_server); 
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 
        $result=ftp_chmod($conn_id, 0777, $file);
        ftp_close($conn_id);
        
        return $result;
    }
function smarty_core_write_compiled_resource($params, &$smarty)
{
    
    
    if(!@is_writable($smarty->compile_dir))
    if(strpos($smarty->compile_dir,"template_c")>0)
    {
        $mymsg="";
        
        $ftpdir=substr($smarty->compile_dir,24);
        if (!myftpchmod($ftpdir, 0777))
        {
            $mymsg=" (cat'n do ftpchmod) "; 
        }
            
    }
    
    if(!@is_writable($smarty->compile_dir)) {
        // compile_dir not writable, see if it exists
        if(!@is_dir($smarty->compile_dir)) {
            $smarty->trigger_error('the $compile_dir \'' . $smarty->compile_dir . '\' does not exist, or is not a directory.', E_USER_ERROR);
            return false;
        }
        $smarty->trigger_error($mymsg.'unable to write compiled to $compile_dir \'' . realpath($smarty->compile_dir) . '\'. Be sure $compile_dir is writable by the web server user.', E_USER_ERROR);
        return false;
    }

    $_params = array('filename' => $params['compile_path'], 'contents' => $params['compiled_content'], 'create_dirs' => true);
    require_once(SMARTY_CORE_DIR . 'core.write_file.php');
    smarty_core_write_file($_params, $smarty);
    return true;
}

/* vim: set expandtab: */

?>
