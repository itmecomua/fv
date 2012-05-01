<?php
class fvErrorHandler
{ 
    public static function ErrorHandler( $errno, $errstr, $errfile, $errline )
    {
        echo  "Error";
        echo  $errno    . "<br>";
        echo  $errstr   . "<br>";
        echo  $errfile  . "(";
        echo  $errline  . ")<br>";
        echo "<hr>";
    }
    
    public static function ExceptionHandler( Exception $error )
    {
        echo "Exception";
        echo  $error->getCode()     . "<br>";
        echo  $error->getMessage()  . "<br>";        
        echo  $error->getFile()     . "(";
        echo  $error->getLine()     . ")<br>";
        echo "<hr>";        
    }

}