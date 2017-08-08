<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * Application config, purely for phpunit testing
 * @package Gz3Base\config
 * @author Andreas Gerhards <ag.dialogue@yahoo.co.nz>
 * @copyright ©2016-2017, Andreas Gerhards - All rights reserved
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 */

$applicationConfig = [
    'modules'=>[
        'Gz3Base\Mvc',
        'Gz3Base\Record',
        'Gz3Base\ServiceManager',
        'Gz3Base\Test'
    ],
    'module_listener_options'=>[
        'module_paths'=>[
            './src',
            'test'=>'./test'
        ],
        'config_glob_paths'=>[
            'config/autoload/{{,*.}global,{,*.}local}.php'
        ],
        'config_cache_enabled'=>false,
        'config_cache_key'=>false,
        'module_map_cache_enabled'=>false,
        'module_map_cache_key'=>false,
        'cache_dir'=>'data/cache/',
    ]
];

return $applicationConfig;

