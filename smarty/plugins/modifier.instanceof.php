<?php
  function smarty_modifier_instanceof( $objectInstance, $className = "Object" )
  {
      return $objectInstance instanceof $className;
  }
?>
