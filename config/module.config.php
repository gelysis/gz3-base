<?php
/**
 * Gz3Base - Zend Framework Base Tweaks / Zend Framework Basis Anpassungen
 * @package Gz3Base\config
 * @author Andreas Gerhards <ag.dialogue@yahoo.co.nz>
 * @copyright Copyright ©2016 Andreas Gerhards
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause - Please check LICENSE.md for more information
 */

$moduleConfig = [
    'di'=>[
        'definition'=>[],
        'instance'=>[]
    ],
    'controllers'=>[
        'initializers'=>[],
        'invokables'=>[]
    ],
    'controller_plugins'=>[
        'factories'=>[],
        'invokables'=>[]
    ]
];

return $moduleConfig;
