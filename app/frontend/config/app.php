<?php 
return array(

    /* список путей к классам */
    'classMapPaths'=> require_once('classMapPaths.php'),

    /* список псевдонимов реальных путей */
    'aliasMapPaths'=> require_once('aliasMapPaths.php'),
    
    /* "Главный" файл модуля */
    'ModuleMainFileName'=> 'module',

    /* "Робочий" файл модуля */
    'ModuleWorkFileName'=> 'action',

    /* Модуль по умолчанию  */
    'DefaultModuleName'=> 'index',

    /* Акшин по умолчанию  */
    'DefaultActionName'=> 'index',
   
    /* Установка свойств класса urlManager  */
    'urlManager'=>array(
        'CaseSensetive'  =>  true,
    ),
    
    /* Установка свойств класса fvApplication  */
    'fvApplication'=>array(
        /* Подключение модулей в Аппликейшн  */
        'ModulesIncluded' =>  array(
            'index'     => 'IndexModule',
            'news'      => 'NewsModule',
        ),

    ),
    
    /* установки для модулей  */
    'modulesConfigs' => require_once('modulesConfigs.php'),
       

);