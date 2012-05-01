<?php 
return array(

    /* список путей к классам */
    'classMapPaths'=> require_once('classMapPaths.php'),

    /* список псевдонимов реальных путей */
    'aliasMapPaths'=> require_once('aliasMapPaths.php'),
    
    /* "Главный" файл модуля */
    'ModuleMainFileName'=> 'action',

    /* "Робочий" файл модуля */
    'ModuleWorkFileName'=> 'module',
   
    /* Установка свойств класса urlManager  */
    'urlManager'=>array(
        'CaseSensetive'  =>  true,
    ),
    
    /* Установка свойств класса fvApplication  */
    'fvApplication'=>array(
        /* Подключение модулей в Аппликейшн  */
        'ModulesIncluded' =>  array(
            'news'  => 'NewsModule',
        ),

    ),
    
    /* установки для модулей  */
    'modulesConfigs' => require_once('modulesConfigs.php'),

);