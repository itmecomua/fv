<?php

define ("STR_BACKTRACE_LENGTH", 100);
define ("E_DATABASE_ERROR", -24);

error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE);

function errorHandler($errno, $errstr='', $errfile='', $errline='')
{
    // if error has been supressed with an @
    if (error_reporting() == 0) {
        return;
    }
    
    $err = false;

    // check if function has been called by an exception
    if(func_num_args() == 5) {
        // called by trigger_error()
        $exception = null;
        list($errno, $errstr, $errfile, $errline) = func_get_args();

        $backtrace = array_reverse(debug_backtrace());
        
    }else {
        
        // caught exception
        $exc = func_get_arg(0);
        
        if ($exc instanceof Exception ) {
            $errno = $exc->getCode();
            $errstr = $exc->getMessage();
            $errfile = $exc->getFile();
            $errline = $exc->getLine();

            $backtrace = $exc->getTrace();
        }
        
        if ($exc instanceof DB_Error) {
            
            $errno = $exc->getCode();
            
            $errstr = $exc->getMessage() . " " . $exc->getDebugInfo();
            
            $backtrace = $exc->getBacktrace();
        }
    }

    $errorType = array (
               E_ERROR            => 'ERROR',
               E_WARNING        => 'WARNING',
               E_PARSE          => 'PARSING ERROR',
               E_NOTICE         => 'NOTICE',
               E_CORE_ERROR     => 'CORE ERROR',
               E_CORE_WARNING   => 'CORE WARNING',
               E_COMPILE_ERROR  => 'COMPILE ERROR',
               E_COMPILE_WARNING => 'COMPILE WARNING',
               E_USER_ERROR     => 'USER ERROR',
               E_USER_WARNING   => 'USER WARNING',
               E_USER_NOTICE    => 'USER NOTICE',
               E_STRICT         => 'STRICT NOTICE',
               E_RECOVERABLE_ERROR  => 'RECOVERABLE ERROR',
               E_DATABASE_ERROR  => 'DATABASE ERROR'
               );
    
    if (!$err) {
	    // create error message
	    if (array_key_exists($errno, $errorType)) {
	        $err = $errorType[$errno];
	    } else {
	        $err = 'CAUGHT EXCEPTION';
	    }
    }
    if ($errno == E_DATABASE_ERROR) {
         $errMsg = "<h6>$err:</h6> \"<B>$errstr</B>\"<BR />";               
    }
    else $errMsg = "<h6>$err:</h6> \"<B>$errstr</B>\" in <FONT color=\"blue\"><B>$errfile</B> on line <B>$errline</B></FONT><BR />";

    $trace = "";
    
    // start backtrace
    foreach ($backtrace as $k => $v) {
        
        $trace .= "<B>#" . ($k + 1) . "</B>: ";
                
        if (isset($v['class'])) {

            $trace .= 'in class '.$v['class'].'::'.$v['function'].'(';

            if (isset($v['args'])) {
                $separator = '';

                foreach($v['args'] as $arg ) {
                    $trace .= "$separator" .getArgument($arg);
                    $separator = ', ';
                }
            }
            $trace .= ')';
        }

        elseif (isset($v['function'])) {
            $trace .= 'in function '.$v['function'].'(';
            if (!empty($v['args'])) {

                $separator = '';

                foreach($v['args'] as $arg ) {
                    $trace .= "$separator" .getArgument($arg);
                    $separator = ', ';
                }
            }
            $trace .= ')';
        }
        $trace .= "\n";        
    }

    // what to do
    switch ($errno) 
    {
        case E_NOTICE:
        case E_USER_NOTICE:
        case E_STRICT:
            return;
            break;

        default:
        {
            //echo "Sorry. We have some error. Go to <a href='http://".fvSite::$fvConfig->get("server_name")."'>start page</a><br/>";
            
                echo '<h2>Debug Msg</h2>'.nl2br($errMsg).'<br />
                <B>Trace</B>: <br />'.nl2br($trace).'<br />';
            
            
             
             $userinfo = "";
             $LoggedUser = fvSite::$fvSession->get ( "login/loggedUser" );
             if ($LoggedUser)
             {
               $userinfo="user id:".$LoggedUser->getPk()."<br>user login:".$LoggedUser->login;
             }
             
             $data = "<hr/><hr/>".date("d-m-Y")."<br>".$url."<br>".$userinfo.'<br><h2>Debug Msg</h2>'.nl2br($errMsg).'<br/><B>Trace</B>: <br />'.nl2br($trace).'<br /><hr/><hr/>'; 
             
             $file = fopen(fvSite::$fvConfig->get("path.error"),"a+");
             if($file)
             {
                fwrite($file,$data);              
             }
             fclose($file);
                 
              /*$url = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
              $data = array();
              $data["CONTENT"] = date("d-m-Y")."<br>".$url."<br>".$userinfo.'<br><h2>Debug Msg</h2>'.nl2br($errMsg).'<br/><B>Trace</B>: <br />'.nl2br($trace).'<br />'; 
              $mail = new Mail("debugmsg",$data);
              $mail->setTheme($url);
             //$mail->SendErrorMail();    
             */
        }
            
                             

            /*if ($errno == E_DATABASE_ERROR) {
                exit(-1);                
            }*/
            break;

    }
} // end of errorHandler()

function displayClientMessage()
{
    echo 'some html page with error message';

}

function getArgument($arg)
{
    switch (strtolower(gettype($arg))) {

        case 'string':
            return( '"'. substr(str_replace( array("\n"), array(''), $arg ), 0, STR_BACKTRACE_LENGTH) . ((strlen($arg) > STR_BACKTRACE_LENGTH)?"...":"") .'"' );

        case 'boolean':
            return (bool)$arg;

        case 'object':
            return 'object('.get_class($arg).')';

        case 'array':
            return  'array(' . count($arg) . ")";

        case 'resource':
            return 'resource('.get_resource_type($arg).')';

        default:
            return var_export($arg, true);
    }
}

$old_error_handler = set_error_handler("errorHandler");

?>