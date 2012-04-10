<?php

class fvDebug
{
   protected function debug($what=false)
   {
      $arrParentInfo = debug_backtrace();
      echo "<pre style='text-align:left; border:1px solid red;'>";
      echo 'File: ' . $arrParentInfo[0]['file'] . ', ';
      echo 'line: ' . $arrParentInfo[0]['line'] . "<br/>";
      if($what !== false)
      {
        if ( is_array( $what ) )  
        {
            print_r ( $what );
        }
        else 
        {
            var_dump ( $what );
        } 
      }
      echo "</pre>";
   }
   
   static function debugs($what=false)
   {
      $arrParentInfo = debug_backtrace();
      echo "<pre style='text-align:left; border:1px solid red;'>";
      echo 'File: ' . $arrParentInfo[0]['file'] . ', ';
      echo 'line: ' . $arrParentInfo[0]['line'] . "<br/>";
      if($what !== false)
      {
        if ( is_array( $what ) )  
        {
            print_r ( $what );
        }
        else 
        {
            var_dump ( $what );
        } 
      }
      echo "</pre>";
   }
}

  
?>
