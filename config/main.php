<?php return array(    /* список апликейшенов */    'appList'           => array(        'backend',    ),    /* Аппликейшн по умалчанию */    'appDefault'           => 'frontend',    /* список путей к классам */    'classMapPaths'     => require_once('classMapPaths.php'),    /* список псевдонимов реальных путей */    'aliasMapPaths'     => require_once('aliasMapPaths.php'),    /* Установка свойств класса fvApplication  */    'fvRequest'         =>array(        'ServerUrlHolder' =>  "REQUEST_URI",    ),            /* Установка свойств класса fvApplication  */    'fvApplication'         =>array(        'DefaultController' =>  "indexATION",    ),    );